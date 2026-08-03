<?php

namespace App\Http\Controllers;

use App\Models\FacturasRelacion;
use App\Exports\ClientesExport;
use App\Exports\ProductosVendidosExport;
use App\Exports\StockExport;
use App\Models\Pedido;
use App\Models\Producto;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    public function ventas(){
        
        $fechaFin = Carbon::now();
        $fechaInicio = request()->input('fecha_inicio') ? Carbon::parse(request()->input('fecha_inicio')) : $fechaFin->copy()->subMonth();
        $fechaFin = request()->input('fecha_fin') ? Carbon::parse(request()->input('fecha_fin')) : $fechaFin;

        $pedidoIds = Pedido::select('id')->whereBetween('created_at', [$fechaInicio, $fechaFin])->pluck('id');
        $facturas = FacturasRelacion::whereIn('pedido_id', $pedidoIds)->where('factura', 'T')->get()
            ->groupBy('pedido_id')
            ->map(function($row) { 
                return $row->first(); 
            })
            ->values();

        $productosVendidos = [];

        foreach ($facturas as $factura) {
            $pedidos = json_decode($factura->pedido, true);
            foreach ($pedidos as $pedido) {
                if ($pedido['cantidad'] > 0) {
                    $codigoProducto = substr($pedido['nombre'], 0, strpos($pedido['nombre'], ' '));
                    $nombreProducto = trim(preg_replace('/^\w+\s/', '', $pedido['nombre']));
                    if (!isset($productosVendidos[$codigoProducto])) {
                        $productosVendidos[$codigoProducto] = [
                            'nombre_producto' => $nombreProducto,
                            'precio_producto' => $pedido['precio'],
                            'cantidad_vendida' => 0,
                            'cantidad_solicitada' => 0
                        ];
                    }
                    try{
                        $productosVendidos[$codigoProducto]['cantidad_vendida'] += $pedido['cantidad'];
                    }catch (\Throwable $th) {
                    
                    }
                }
            }
            // Obtener el pedido y calcular la cantidad solicitada
            $pedidoRelacion = Pedido::find($factura->pedido_id);
            $items = json_decode($pedidoRelacion->pedido, true);
            foreach ($items as $item) {
                $codigoProducto = $item['codigo'];
                try{
                    $productosVendidos[$codigoProducto]['cantidad_solicitada'] += $item['cantidad'];
                }catch (\Throwable $th) {
                    
                }
            }
        }

        // Ordena los productos por código
        ksort($productosVendidos);

        $fechaInicio = $fechaInicio->format('d-m-Y'); $fechaFin = $fechaFin->format('d-m-Y');
        return view('adm.estadistica.ventas', compact('productosVendidos', 'fechaInicio', 'fechaFin'));

    }

    public function clientes()
    {
        $fechaFin = Carbon::now();
        $fechaInicio = request()->input('fecha_inicio') ? Carbon::parse(request()->input('fecha_inicio')) : $fechaFin->copy()->subMonth();
        $fechaFin = request()->input('fecha_fin') ? Carbon::parse(request()->input('fecha_fin')) : $fechaFin;

        $fechaInicioFormatted = $fechaInicio->format('d-m-Y');
        $fechaFinFormatted = $fechaFin->format('d-m-Y');
        $pedidoIds = Pedido::select('id')->whereBetween('created_at', [$fechaInicio, $fechaFin])->pluck('id');
        $facturas = FacturasRelacion::whereIn('pedido_id', $pedidoIds)
            ->where('factura', 'T')
            ->with(['pedidomod.cliente']) 
            ->get()
            ->groupBy('pedido_id') 
            ->map(function($row) { return $row->first(); })
            ->filter(function ($factura) { 
                return $factura->pedidomod && $factura->pedidomod->cliente; // Filtra facturas con pedidos y clientes válidos
            });

            $clientes = $facturas->groupBy(function ($factura) { 
                return $factura->pedidomod->usuario_id; // Usar usuario_id para agrupar por cliente 
            })->map(function ($facturas, $clienteId) { 
                $cliente = $facturas->first()->pedidomod->cliente; 
                $total = $facturas->sum('subtotal'); // Sumar el campo 'total' de las facturas 
                return [ 'cliente' => $cliente, 'total' => $total ]; 
            })->sortByDesc('total');

        // Pasa los datos a la vista
        return view('adm.estadistica.clientes', [
            'clientes' => $clientes,
            'fechaInicio' => $fechaInicioFormatted,
            'fechaFin' => $fechaFinFormatted
        ]);
    }

	public function exportarClientes()
    {
        $fechaFin = Carbon::now();
        $fechaInicio = request()->input('fecha_inicio') ? Carbon::parse(request()->input('fecha_inicio')) : $fechaFin->copy()->subMonth();
        $fechaFin = request()->input('fecha_fin') ? Carbon::parse(request()->input('fecha_fin')) : $fechaFin;

        $fechaInicioFormatted = $fechaInicio->format('d-m-Y');
            $fechaFinFormatted = $fechaFin->format('d-m-Y');
            $pedidoIds = Pedido::select('id')->whereBetween('created_at', [$fechaInicio, $fechaFin])->pluck('id');
            $facturas = FacturasRelacion::whereIn('pedido_id', $pedidoIds)
                ->where('factura', 'T')
                ->with(['pedidomod.cliente']) 
                ->get()
                ->groupBy('pedido_id') 
                ->map(function($row) { return $row->first(); })
                ->filter(function ($factura) { 
                    return $factura->pedidomod && $factura->pedidomod->cliente; // Filtra facturas con pedidos y clientes válidos
                });

                $clientes = $facturas->groupBy(function ($factura) { 
                    return $factura->pedidomod->usuario_id; // Usar usuario_id para agrupar por cliente 
                })->map(function ($facturas, $clienteId) { 
                    $cliente = $facturas->first()->pedidomod->cliente; 
                    $total = $facturas->sum('subtotal'); // Sumar el campo 'total' de las facturas 
                    return [ 'cliente' => $cliente, 'total' => $total ]; 
                })->sortByDesc('total');

        return Excel::download(new ClientesExport($clientes), 'clientes.xlsx');
    }
	
	
    public function exportarProductosVendidos(Request $request)
    {
        $fechaFin = Carbon::now();
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio')) : $fechaFin->copy()->subMonth();
        $fechaFin = $request->input('fecha_fin') ? Carbon::parse($request->input('fecha_fin')) : $fechaFin;

        $pedidoIds = Pedido::select('id')->whereBetween('created_at', [$fechaInicio, $fechaFin])->pluck('id');
        $facturas = FacturasRelacion::whereIn('pedido_id', $pedidoIds)->where('factura', 'T')->get()
            ->groupBy('pedido_id')
            ->map(function($row) { 
                return $row->first(); 
            })
            ->values();

        $productosVendidos = [];

        foreach ($facturas as $factura) {
            $pedidos = json_decode($factura->pedido, true);
            foreach ($pedidos as $pedido) {
                if ($pedido['cantidad'] > 0) {
                    if (isset($pedido['codigo']) && $pedido['codigo']) {
                        // NUEVO FORMATO
                        $codigoProducto = $pedido['codigo'];
                        $nombreProducto = $pedido['nombre'];
                    } else {
                        // FORMATO ANTIGUO
                        $codigoProducto = substr($pedido['nombre'], 0, strpos($pedido['nombre'], ' ') ?: strlen($pedido['nombre']));
                        $nombreProducto = trim(preg_replace('/^\\w+\\s/', '', $pedido['nombre']));
                    }

                    if (!isset($productosVendidos[$codigoProducto])) {
                        $productosVendidos[$codigoProducto] = [
                            'nombre_producto' => $nombreProducto,
                            'precio_producto' => $pedido['precio'],
                            'cantidad_vendida' => 0,
                            'cantidad_solicitada' => 0
                        ];
                    }
					try{
                    	$productosVendidos[$codigoProducto]['cantidad_vendida'] += $pedido['cantidad'];
					}catch (\Throwable $th) {
                    
                	}
                }
            }
            // Obtener el pedido y calcular la cantidad solicitada
            $pedidoRelacion = Pedido::find($factura->pedido_id);
            $items = json_decode($pedidoRelacion->pedido, true);
            foreach ($items as $item) {
                $codigoProducto = $item['codigo'];
                try{
                    $productosVendidos[$codigoProducto]['cantidad_solicitada'] += $item['cantidad'];
                }catch (\Throwable $th) {
                    
                }
            }
        }

        ksort($productosVendidos);

        return Excel::download(new ProductosVendidosExport($productosVendidos, $fechaInicio, $fechaFin), 'productos_vendidos.xlsx');
    }

    public function calcularSubtotales()
    {
        $pedidos = FacturasRelacion::select('pedido_id')->where('factura', 'T')->pluck('pedido_id');
        foreach ($pedidos as $pedido){
            $pedidoId = $pedido;
            
            // Obtener el pedido y el cliente relacionado
            $factura = FacturasRelacion::where('pedido_id', $pedidoId)
            ->where('factura', 'T')
            ->with(['pedidomod.cliente'])
            ->first();
            try {
                $cliente = $factura->pedidomod->cliente;
                $descuento = $cliente->descuento;

                // Calcular el total del pedido con el descuento
                $items = json_decode($factura->pedido);
                $total = 0;
                
                foreach ($items as $item) {
                $subtotal = $item->precio * $item->cantidad;
                $total += $subtotal;
                }

                $totalConDescuento = $total - ($total * ($descuento / 100));
                $descuento = $total * ($descuento / 100);
                $factura->subtotal=$totalConDescuento;
                $factura->descuento = $descuento;
                $factura->save();
                echo "Total: $total\n";
                echo "Total con descuento: $totalConDescuento\n";
            } catch (\Throwable $th) {
                echo $factura->id."<br>";
            }

            
        }

    }

	public function stock()
    {
        $productos = Producto::with('presentaciones')->get();
        return view('adm.estadistica.stock', compact('productos'));
    }

    public function export()
    { 
        return Excel::download(new StockExport, 'stock.xlsx');
    }
	
	public function grafventas()
    {
    $fechaFin = Carbon::now();
    $fechaInicio = request()->input('fecha_inicio') ? Carbon::parse(request()->input('fecha_inicio')) : $fechaFin->copy()->subMonth();
    $fechaFin = request()->input('fecha_fin') ? Carbon::parse(request()->input('fecha_fin')) : $fechaFin;

    // Obtener todos los productos vendidos en el rango de fechas
    $pedidoIds = Pedido::select('id')->whereBetween('created_at', [$fechaInicio, $fechaFin])->pluck('id');
    $facturas = FacturasRelacion::whereIn('pedido_id', $pedidoIds)->where('factura', 'T')->get()
        ->groupBy('pedido_id')
        ->map(function($row) { 
            return $row->first(); 
        })
        ->values();

    $productosVendidos = [];

    foreach ($facturas as $factura) {
        $pedidos = json_decode($factura->pedido, true);
        foreach ($pedidos as $pedido) {
            if ($pedido['cantidad'] > 0) {
                $codigoProducto = substr($pedido['nombre'], 0, strpos($pedido['nombre'], ' '));
                $nombreProducto = trim(preg_replace('/^\w+\s/', '', $pedido['nombre']));
                
                if (!isset($productosVendidos[$codigoProducto])) {
                    $productosVendidos[$codigoProducto] = [
                        'nombre_producto' => $nombreProducto,
                        'precio_producto' => $pedido['precio'],
                        'cantidad_vendida' => 0,
                        'cantidad_solicitada' => 0
                    ];
                }
                try {
                    $productosVendidos[$codigoProducto]['cantidad_vendida'] += $pedido['cantidad'];
                } catch (\Throwable $th) {
                    // Manejo de errores
                }
            }
        }

        // Obtener el pedido y calcular la cantidad solicitada
        $pedidoRelacion = Pedido::find($factura->pedido_id);
        if ($pedidoRelacion) {
            $items = json_decode($pedidoRelacion->pedido, true);
            foreach ($items as $item) {
                $codigoProducto = $item['codigo'];
                try {
                    if (isset($productosVendidos[$codigoProducto])) {
                        $productosVendidos[$codigoProducto]['cantidad_solicitada'] += $item['cantidad'];
                    }
                } catch (\Throwable $th) {
                    // Manejo de errores
                }
            }
        }
    }
    // Preparar los datos para el gráfico
    $labels = [];
    $cantidadVendida = [];
    $cantidadSolicitada = [];

    foreach ($productosVendidos as $producto) {
        if ($producto['cantidad_vendida'] > 0 || $producto['cantidad_solicitada'] > 0) { // Solo incluir productos con datos
            $labels[] = $producto['nombre_producto'];
            $cantidadVendida[] = $producto['cantidad_vendida'];
            $cantidadSolicitada[] = $producto['cantidad_solicitada'];
        }
    }

    //Clientes:
        $pedidoIds = Pedido::select('id')->whereBetween('created_at', [$fechaInicio, $fechaFin])->pluck('id');
        $facturas = FacturasRelacion::whereIn('pedido_id', $pedidoIds)
            ->where('factura', 'T')
            ->with(['pedidomod.cliente']) 
            ->get()
            ->groupBy('pedido_id') 
            ->map(function($row) { return $row->first(); })
            ->filter(function ($factura) { 
                return $factura->pedidomod && $factura->pedidomod->cliente; // Filtra facturas con pedidos y clientes válidos
            });

            $clientes = $facturas->groupBy(function ($factura) { 
                return $factura->pedidomod->usuario_id; // Usar usuario_id para agrupar por cliente 
            })->map(function ($facturas, $clienteId) { 
                $cliente = $facturas->first()->pedidomod->cliente; 
                $total = $facturas->sum('subtotal'); // Sumar el campo 'total' de las facturas 
                return [ 'cliente' => $cliente, 'total' => $total ]; 
            })->sortByDesc('total');

            // Preparar los datos para el gráfico
            $clienteLabels = [];
            $clienteTotals = [];

            foreach ($clientes as $cliente) {
                $clienteLabels[] = $cliente['cliente']->nombre; // Ajusta según la propiedad correcta del cliente
                $clienteTotals[] = $cliente['total'];
            }
            
        return view('adm.estadistica.graficoventa', compact('labels', 'cantidadVendida', 'cantidadSolicitada', 'fechaInicio', 'fechaFin', 'clientes','clienteLabels','clienteTotals' ));
    }
}

