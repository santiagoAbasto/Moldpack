<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\CarritoContenido;
use App\Models\Cliente;
use stdClass;
use Illuminate\Support\Facades\DB;

include_once dirname(__FILE__).'/afipsdk/src/Afip.php';

class ContabilidadController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $afip_ws;

    public function __construct()
    {
        $this->middleware('auth');        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
*/
    private function normalizarPedidos($pedidos)
    {
        foreach ($pedidos as $item) {
            $item->nombre = $item->nombre ?: 'Cliente eliminado';
            $item->razonSocial = $item->razonSocial ?: 'Cliente eliminado #' . $item->usuario_id;
            $item->username = $item->username ?: 'cliente_eliminado_' . $item->usuario_id;

            $productos = json_decode($item->pedido) ?: [];
            foreach ($productos as $producto) {
                if (!isset($producto->cantidad_original_cliente)) {
                    $producto->cantidad_original_cliente = intval($producto->cantidad ?? 0);
                }
                if (!isset($producto->cantidad_preparada_logistica)) {
                    $producto->cantidad_preparada_logistica = intval($producto->cantidad ?? 0);
                }
            }
            $item->pedido = collect($productos)->values()->all();
        }

        return $pedidos;
    }

    private function cantidadEntera($valor)
    {
        return max(0, intval($valor ?? 0));
    }

    private function indicePedidoPorId(array $items, $idItem)
    {
        foreach ($items as $index => $item) {
            $idPedido = $item->idPedido ?? $index;
            if ((string) $idPedido === (string) $idItem) {
                return $index;
            }
        }

        return null;
    }

    private function recalcularTotalPedido(Pedido $pedido, array $items)
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $precio = is_numeric($item->precio_congelado ?? null)
                ? floatval($item->precio_congelado)
                : floatval($item->precio ?? 0);
            $subtotal += $precio * $this->cantidadEntera($item->cantidad ?? 0);
        }

        $cliente = Cliente::find($pedido->usuario_id);
        $descuento = 1;
        if ($cliente && $cliente->descuento != 0 && $cliente->descuento !== null) {
            $descuento = (100 - $cliente->descuento) / 100;
        }

        $carrito = CarritoContenido::first();
        $iva = $carrito ? ((floatval($carrito->iva) / 100) + 1) : 1;

        return round($subtotal * $descuento * $iva, 2);
    }

    private function aplicarFiltrosPedidos($query, Request $request, array $estadosPermitidos = [])
    {
        $busqueda = trim((string) $request->query('q', ''));

        if ($busqueda !== '') {
            $query->where(function ($subquery) use ($busqueda) {
                if (ctype_digit($busqueda)) {
                    $subquery->orWhere('pedidos.id', (int) $busqueda)
                        ->orWhere('pedidos.usuario_id', (int) $busqueda);
                }

                $subquery->orWhere('clientes.razonSocial', 'LIKE', "%{$busqueda}%")
                    ->orWhere('clientes.nombre', 'LIKE', "%{$busqueda}%")
                    ->orWhere('clientes.username', 'LIKE', "%{$busqueda}%")
                    ->orWhere('pedidos.estado', 'LIKE', "%{$busqueda}%")
                    ->orWhere('pedidos.fecha', 'LIKE', "%{$busqueda}%")
                    ->orWhere('pedidos.mensaje', 'LIKE', "%{$busqueda}%")
                    ->orWhere('pedidos.pedido', 'LIKE', "%{$busqueda}%");
            });
        }

        $estado = $request->query('estado');
        if ($estado !== null && $estado !== '' && (empty($estadosPermitidos) || in_array($estado, $estadosPermitidos, true))) {
            $query->where('pedidos.estado', $estado);
        }

        return $query;
    }

    public function pedido(Request $request){
        //$pedidos = pedido::leftJoin(('clientes','pedido.usuario_id','=','clientes.id')->get();

        $estados = ['APROBADO', 'FACTURADO PENDIENTES'];
        $pedidos = $this->aplicarFiltrosPedidos(
            Pedido::select('clientes.username','clientes.razonSocial','clientes.descuento','clientes.nombre','pedidos.*')
                ->leftJoin('clientes','pedidos.usuario_id','=','clientes.id')
                ->whereIn('pedidos.estado', $estados),
            $request,
            $estados
        )
            ->orderby('pedidos.id','desc')
            ->paginate(12)
            ->appends($request->query());
        
        $carrito = CarritoContenido::first();
        $this->normalizarPedidos($pedidos);
        
        return view('adm.facturacion.form_pedidos',compact('pedidos','carrito','estados'));
    }
  
    public function pedido_post(){
        $pedidos = Pedido::all();

        return redirect()->route('pedido');
    }
    public function pedido_put(Request $request){
        $response = $request;

        DB::transaction(function () use ($request) {
            $pedidos = Pedido::lockForUpdate()->findOrFail($request->id);
            $pedidos->estado = "ENTREGADO";
            $pedidos->save();
        });

        $response['msj'] = "MODIFICADO";

        return $response;
    }
    public function pedido_delete(Request $request, $id){
        $pedidos = Pedido::findorFail($id);
        
        $pedidos->estado = 'CANCELADO';
        $pedidos->save();

        return redirect()->route('adm.facturacion')->with('success', 'Pedido anulado.');
    }
    public function update(Request $request){
        $respuesta = "Registro modificado";

        DB::transaction(function () use ($request, &$respuesta) {
            $pedidos = Pedido::lockForUpdate()->findOrFail($request->id);
            $arrPedido = json_decode($pedidos->pedido) ?: [];
            $indice = $this->indicePedidoPorId($arrPedido, $request->idItem);

            if ($indice === null) {
                $respuesta = "No se encontro el item del pedido";
                return;
            }

            $item = $arrPedido[$indice];
            if (!isset($item->cantidad_original_cliente)) {
                $item->cantidad_original_cliente = $this->cantidadEntera($item->cantidad ?? 0);
            }
            if (!isset($item->cantidad_preparada_logistica)) {
                $item->cantidad_preparada_logistica = $this->cantidadEntera($item->cantidad ?? 0);
            }

            $precio = is_numeric($request->precio)
                ? round(floatval($request->precio), 2)
                : round(floatval($item->precio_congelado ?? $item->precio ?? 0), 2);

            $item->codigo = $request->codigo ?? $item->codigo;
            $item->nombre = $request->nombre ?? $item->nombre;
            $item->precio = $precio;
            $item->precio_congelado = $precio;
            $item->cantidad = $this->cantidadEntera($request->cantidad ?? $item->cantidad ?? 0);

            $arrPedido[$indice] = $item;
            $pedidos->pedido = json_encode($arrPedido);
            $pedidos->total = $this->recalcularTotalPedido($pedidos, $arrPedido);
            $pedidos->save();
        });

        return $respuesta;
    }
}
