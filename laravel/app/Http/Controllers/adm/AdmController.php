<?php

namespace App\Http\Controllers\adm;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PresentacionRelacion;
use App\Models\Producto;
use App\Models\User;
use App\Support\AdminDashboardAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdmController extends Controller
{
    public function __construct()
     {
         $this->middleware('auth');
     }


    public function index()
    {
        $dashboard = $this->dashboardData();

        return view('adm.index', compact('dashboard'));
    }

    public function data()
    {
        return response()->json($this->dashboardData());
    }

    private function dashboardData(): array
    {
        if (AdminDashboardAccess::dashboardTypeFor(auth()->user()) !== AdminDashboardAccess::SALES) {
            return $this->webTrafficDashboardData();
        }

        return $this->salesDashboardData();
    }

    private function salesDashboardData(): array
    {
        $trendStart = now()->subDays(13)->startOfDay();
        $facturadoStates = ['FACTURADO', 'FACTURADO PENDIENTES'];
        $monthStart = now()->startOfMonth();

        $rawOrderTrend = Pedido::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('COUNT(*) as pedidos')
            )
            ->whereNotNull('created_at')
            ->where('created_at', '>=', $trendStart)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $rawBillingTrend = Pedido::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('COALESCE(SUM(total), 0) as facturacion')
            )
            ->whereNotNull('created_at')
            ->where('created_at', '>=', $trendStart)
            ->whereIn('estado', $facturadoStates)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $pedidosPorDia = collect(range(0, 13))->map(function ($dayOffset) use ($trendStart, $rawOrderTrend, $rawBillingTrend) {
            $date = $trendStart->copy()->addDays($dayOffset);
            $key = $date->toDateString();
            $orderRow = $rawOrderTrend->get($key);
            $billingRow = $rawBillingTrend->get($key);

            return [
                'fecha' => $date->format('d/m'),
                'pedidos' => (int) optional($orderRow)->pedidos,
                'facturacion' => (float) optional($billingRow)->facturacion,
            ];
        })->values();

        $estadoCounts = Pedido::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'estado' => $item->estado ?: 'Sin estado',
                    'total' => (int) $item->total,
                ];
            });

        $ultimosPedidos = Pedido::select(
                'pedidos.id',
                'pedidos.estado',
                'pedidos.total',
                'pedidos.fecha',
                'pedidos.created_at',
                'clientes.razonSocial',
                'clientes.nombre',
                'clientes.username'
            )
            ->leftJoin('clientes', 'pedidos.usuario_id', '=', 'clientes.id')
            ->orderBy('pedidos.id', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($pedido) {
                return [
                    'id' => $pedido->id,
                    'cliente' => $pedido->razonSocial ?: $pedido->nombre ?: $pedido->username ?: 'Cliente eliminado',
                    'estado' => $pedido->estado,
                    'fecha' => $pedido->fecha ?: optional($pedido->created_at)->format('d/m/Y'),
                    'total' => (float) $pedido->total,
                ];
            });

        $clientesFrecuentes = Pedido::select(
                'pedidos.usuario_id',
                'clientes.razonSocial',
                'clientes.nombre',
                'clientes.username',
                DB::raw('COUNT(pedidos.id) as pedidos_count'),
                DB::raw('COALESCE(SUM(pedidos.total), 0) as total_comprado'),
                DB::raw('MAX(pedidos.id) as ultimo_pedido')
            )
            ->leftJoin('clientes', 'pedidos.usuario_id', '=', 'clientes.id')
            ->whereNotNull('pedidos.usuario_id')
            ->groupBy('pedidos.usuario_id', 'clientes.razonSocial', 'clientes.nombre', 'clientes.username')
            ->orderByDesc('pedidos_count')
            ->limit(6)
            ->get()
            ->map(function ($cliente) {
                return [
                    'cliente' => $cliente->razonSocial ?: $cliente->nombre ?: $cliente->username ?: 'Cliente eliminado',
                    'pedidos' => (int) $cliente->pedidos_count,
                    'total' => (float) $cliente->total_comprado,
                    'ultimo_pedido' => $cliente->ultimo_pedido,
                ];
            });

        return [
            'dashboard_type' => 'sales',
            'metrics' => [
                'pedidos_total' => Pedido::count(),
                'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
                'pedidos_armando' => Pedido::where('estado', 'ARMANDO')->count(),
                'pedidos_aprobados' => Pedido::where('estado', 'APROBADO')->count(),
                'pedidos_facturados' => Pedido::whereIn('estado', $facturadoStates)->count(),
                'facturacion_total' => (float) Pedido::whereIn('estado', $facturadoStates)->sum('total'),
                'facturacion_mensual' => (float) Pedido::whereIn('estado', $facturadoStates)
                    ->where('created_at', '>=', $monthStart)
                    ->sum('total'),
                'clientes_activos' => Cliente::where('estado', 1)->count(),
                'productos_activos' => Producto::where('activa', '!=', 0)->count(),
                'stock_critico' => PresentacionRelacion::where('stock', '<=', 0)->count(),
                'usuarios_internos' => User::count(),
            ],
            'estados' => $estadoCounts,
            'pedidos_por_dia' => $pedidosPorDia,
            'ultimos_pedidos' => $ultimosPedidos,
            'clientes_frecuentes' => $clientesFrecuentes,
            'modulos' => collect(\App\Support\AdminModules::modulesForUser(auth()->user()))
                ->map(function ($module, $key) {
                    return [
                        'key' => $key,
                        'label' => $module['label'],
                        'icon' => $module['icon'],
                    ];
                })
                ->values(),
            'updated_at' => now()->format('d/m/Y H:i:s'),
        ];
    }

    private function webTrafficDashboardData(): array
    {
        $traffic = $this->readTrafficCounters();
        $start = now()->subDays(13)->startOfDay();

        $trafficSeries = collect(range(0, 13))->map(function ($dayOffset) use ($start, $traffic) {
            $date = $start->copy()->addDays($dayOffset);
            $key = $date->toDateString();
            $day = $traffic[$key] ?? [];
            $sections = $day['secciones'] ?? [];

            return [
                'fecha' => $date->format('d/m'),
                'visitas' => (int) ($day['visitas'] ?? 0),
                'busquedas' => (int) ($sections['buscar'] ?? 0),
            ];
        })->values();

        return [
            'dashboard_type' => 'web_traffic',
            'web_traffic' => $trafficSeries,
            'updated_at' => now()->format('d/m/Y H:i:s'),
        ];
    }

    private function readTrafficCounters(): array
    {
        $path = storage_path('app/analytics/web-traffic.json');

        if (!File::exists($path)) {
            return [];
        }

        $data = json_decode((string) File::get($path), true);

        return is_array($data) ? $data : [];
    }
}
