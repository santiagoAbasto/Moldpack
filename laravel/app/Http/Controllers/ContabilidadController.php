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
            $item->pedido = collect($productos)->values()->all();
        }

        return $pedidos;
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
        $pedidos = Pedido::findorFail($request->id);
        $arrPedido = json_decode($pedidos->pedido);
        $arrProductos = array();
        foreach($arrPedido as $item){
            if($request->cantidad != 0){
                $producto = new stdClass;
                if($request->idItem == $item->idPedido){
                    $producto->codigo = $item->codigo;
                    $producto->nombre = $request->nombre;
                    $producto->precio = $request->precio;
                    $producto->id = $item->id;
                    $producto->idPedido = $request->idItem;
                    $producto->cantidad = $request->cantidad;
                }else{                
                    $producto->codigo = $item->codigo;
                    $producto->nombre = $item->nombre;
                    $producto->precio = $item->precio;
                    $producto->id = $item->id;
                    $producto->idPedido = $item->idPedido;
                    $producto->cantidad = $item->cantidad;
                }
                array_push($arrProductos,$producto);
            }
        }
        $arrPedido = json_encode($arrProductos);
        $pedidos->pedido = $arrPedido;
        $pedidos->save();
        
        return "Registro modificado";
    }
}
