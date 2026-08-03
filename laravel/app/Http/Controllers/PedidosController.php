<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\PresentacionRelacion;
use App\Models\Cliente;
use App\Models\CarritoContenido;
use stdClass;
use App\Exports\PedidoExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
*/
    private function pedidosConCliente()
    {
        return Pedido::select('clientes.nombre', 'clientes.apellido', 'clientes.username', 'clientes.razonSocial', 'pedidos.*')
            ->leftJoin('clientes', 'pedidos.usuario_id', '=', 'clientes.id');
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
                    ->orWhere('clientes.apellido', 'LIKE', "%{$busqueda}%")
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

    private function aplicarFiltrosFacturas($query, Request $request, array $estadosPermitidos = [])
    {
        $busqueda = trim((string) $request->query('q', ''));

        if ($busqueda !== '') {
            $query->where(function ($subquery) use ($busqueda) {
                if (ctype_digit($busqueda)) {
                    $subquery->orWhere('id', (int) $busqueda)
                        ->orWhere('usuario_id', (int) $busqueda);
                }

                $subquery->orWhere('estado', 'LIKE', "%{$busqueda}%")
                    ->orWhere('fecha', 'LIKE', "%{$busqueda}%")
                    ->orWhere('mensaje', 'LIKE', "%{$busqueda}%")
                    ->orWhere('pedido', 'LIKE', "%{$busqueda}%")
                    ->orWhereHas('cliente', function ($cliente) use ($busqueda) {
                        $cliente->where('razonSocial', 'LIKE', "%{$busqueda}%")
                            ->orWhere('nombre', 'LIKE', "%{$busqueda}%")
                            ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                            ->orWhere('username', 'LIKE', "%{$busqueda}%");
                    })
                    ->orWhereHas('obtenerRelacionados', function ($factura) use ($busqueda) {
                        $factura->where('numeroFactura', 'LIKE', "%{$busqueda}%")
                            ->orWhere('relacion_id', 'LIKE', "%{$busqueda}%")
                            ->orWhere('factura', 'LIKE', "%{$busqueda}%")
                            ->orWhere('estado', 'LIKE', "%{$busqueda}%");
                    });
            });
        }

        $estado = $request->query('estado');
        if ($estado !== null && $estado !== '' && (empty($estadosPermitidos) || in_array($estado, $estadosPermitidos, true))) {
            $query->where('estado', $estado);
        }

        return $query;
    }

    private function normalizarPedidos($pedidos, $sortByPrice = false)
    {
        foreach ($pedidos as $item) {
            $item->nombre = $item->nombre ?: 'Cliente eliminado';
            $item->razonSocial = $item->razonSocial ?: 'Cliente eliminado #' . $item->usuario_id;
            $item->username = $item->username ?: 'cliente_eliminado_' . $item->usuario_id;

            $productos = json_decode($item->pedido) ?: [];
            $coleccion = collect($productos);
            $productos = $sortByPrice
                ? $coleccion->sortBy([['codigo', SORT_NATURAL], ['precio', 'asc']])->values()->all()
                : $coleccion->sortBy([['codigo', SORT_NATURAL]])->values()->all();

            foreach ($productos as $producto) {
                if (!isset($producto->presentacionid)) {
                    continue;
                }

                $presentacion = PresentacionRelacion::find($producto->presentacionid);
                if ($presentacion) {
                    $producto->stock = $presentacion->stock;
                    $producto->precio = $presentacion->precio;
                }
            }

            $item->pedido = $productos;
        }

        return $pedidos;
    }

    public function pedido(Request $request){
        //$pedidos = pedido::leftJoin(('clientes','pedido.usuario_id','=','clientes.id')->get();

        $estados = ['pendiente', 'ARMANDO'];
        $pedidos = $this->aplicarFiltrosPedidos(
            $this->pedidosConCliente()->whereIn('pedidos.estado', $estados),
            $request,
            $estados
        )
            ->orderby('pedidos.id','desc')
            ->paginate(12)
            ->appends($request->query());
        $clientes = Cliente::orderBy('id','asc')->get();
        $arrProductos = Producto::orderBy('categorias_id','asc')->orderBy('subcategorias_id','asc')->get();
        $this->normalizarPedidos($pedidos);
        return view('adm.pedidos.form_pedidos',compact('pedidos','clientes','arrProductos','estados'));
    }
public function pedidoAll(Request $request){
        //$pedidos = pedido::leftJoin(('clientes','pedido.usuario_id','=','clientes.id')->get();

        $estados = ['pendiente', 'ARMANDO', 'APROBADO', 'FACTURADO', 'FACTURADO PENDIENTES', 'ENTREGADO', 'CANCELADO'];
        $pedidos = $this->aplicarFiltrosPedidos($this->pedidosConCliente(), $request, $estados)
            ->orderby('pedidos.id','desc')
            ->paginate(12)
            ->appends($request->query());
        $clientes = Cliente::orderBy('id','asc')->get();
        $arrProductos = Producto::orderBy('categorias_id','asc')->orderBy('subcategorias_id','asc')->get();
        $this->normalizarPedidos($pedidos, true);
        return view('adm.facturacion.form_pedidosAll',compact('pedidos','clientes','arrProductos','estados'));
    }
    public function facturado(Request $request){
        //$pedidos = pedido::leftJoin(('clientes','pedido.usuario_id','=','clientes.id')->get();
        $estados = ['FACTURADO', 'FACTURADO PENDIENTES'];
        $pedidos = $this->aplicarFiltrosPedidos(
            $this->pedidosConCliente()->whereIn('pedidos.estado', $estados),
            $request,
            $estados
        )
            ->orderby('pedidos.id','desc')
            ->paginate(12)
            ->appends($request->query());
        $clientes = Cliente::orderBy('id','asc')->get();
        $arrProductos = Producto::orderBy('categorias_id','asc')->orderBy('subcategorias_id','asc')->get();
        $this->normalizarPedidos($pedidos, true);
        return view('adm.facturado.form_pedidos',compact('pedidos','clientes','arrProductos','estados'));
    }
    public function pedido_post(){
        $pedidos = Pedido::all();

        return redirect()->route('pedido');
    }
    public function pedido_put(Request $request){
        $response = ['msj' => 'MODIFICADO'];

        DB::transaction(function () use ($request, &$response) {
            $carrito = CarritoContenido::first();
            $pedidos = Pedido::lockForUpdate()->findOrFail($request->id);

            if (in_array($pedidos->estado, ['APROBADO', 'FACTURADO', 'FACTURADO PENDIENTES', 'ENTREGADO'], true)) {
                $response['msj'] = 'SIN CAMBIOS';
                return;
            }

            $cliente = Cliente::find($pedidos->usuario_id);
            $arrPedido = json_decode($pedidos->pedido) ?: [];
            $total_pedido = 0;

            foreach ($arrPedido as $pedido) {
                if (!isset($pedido->presentacionid)) {
                    continue;
                }

                $presentacion = PresentacionRelacion::lockForUpdate()->find($pedido->presentacionid);
                if (!$presentacion) {
                    continue;
                }

                $cantidad = max(0, intval($pedido->cantidad ?? 0));
                $presentacion->stock = intval($presentacion->stock) - $cantidad;
                $presentacion->save();

                $pedido->precio = $presentacion->precio;
                $pedido->stock = $presentacion->stock;
                $total_pedido += floatval($presentacion->precio) * $cantidad;
            }

            $descuento = 1;
            if ($cliente && $cliente->descuento != 0 && $cliente->descuento !== null) {
                $descuento = (100 - $cliente->descuento) / 100;
            }

            $iva = $carrito ? ((floatval($carrito->iva) / 100) + 1) : 1;

            $pedidos->estado = "APROBADO";
            $pedidos->pedido = json_encode($arrPedido);
            $pedidos->total = $total_pedido * $descuento * $iva;
            $pedidos->save();
        });

        return response()->json($response);
    }
    public function pedido_put2(Request $request){
        $response = $request;

        $pedidos = Pedido::findorFail($request->id);        
        $pedidos->estado = "ARMANDO";        
        $pedidos->save();

        $response['msj'] = "MODIFICADO";

        return $response;
    }
	public function pedido_putAprobado(Request $request){
        return $this->pedido_put($request);
    }
	public function pedido_bulto(Request $request){
        $response = $request;

        $pedidos = Pedido::findorFail($request->id);        
        $pedidos->bultos = $request->bultos;
        $pedidos->save();

        //$response['msj'] = "MODIFICADO";

        return $response;
    }
    public function pedido_delete(Request $request, $id){
        $pedidos = Pedido::findorFail($id);
        
        $pedidos->estado = 'CANCELADO';
        $pedidos->save();

        return redirect()->route('pedido')->with('success', 'Pedido anulado.');
    }
	    public function updateAddProduct(Request $request){
        $pedido = Pedido::findorFail($request->pedido);
        $presentacion = $request->producto;
        $arrPresentacion = explode('-',$request->producto);
        $auxPresentacion = PresentacionRelacion::findorFail($arrPresentacion[0]);
        $producto = Producto::find($auxPresentacion->producto_id);
        $auxCart = json_decode($pedido->pedido,true) ?: [];
        $idPedido = count($auxCart);
        $auxItem = new stdClass;
        $auxItem->codigo = $auxPresentacion->codigo;
        $auxItem->nombre = $producto->nombre." ".$auxPresentacion->presentacion;
        $auxItem->precio = $auxPresentacion->precio;
        $auxItem->presentacionid = $auxPresentacion->id;
        $auxItem->stock = $auxPresentacion->stock;
        $auxItem->cantidad = $request->cantidad;
        $auxItem->idPedido = $idPedido;
        array_push($auxCart,$auxItem);
        $pedido->pedido = json_encode($auxCart);
        $pedido->save();
        return $pedido;
        
    }
    public function update(Request $request){
        $pedidos = Pedido::findorFail($request->id);
        $precentacion = PresentacionRelacion::findorFail($request->idPresentacion);
        $arrPedido = json_decode($pedidos->pedido);
        $arrPedido[$request->idItem]->cantidad = $request->cantidad;
		$arrPedido[$request->idItem]->cantidadF = $request->cantidadF;
        $arrPedido[$request->idItem]->nombre = $request->nombre;
        $arrPedido[$request->idItem]->precio = $precentacion->precio;
        $arrPedido[$request->idItem]->presentacionid = $precentacion->id;
        $arrPedido[$request->idItem]->codigo = $precentacion->codigo;
        $arrPedido[$request->idItem]->id = $precentacion->producto_id;
        $arrPedido[$request->idItem]->stock = $precentacion->stock;
 
        $arrPedido = json_encode($arrPedido);
        $pedidos->pedido = $arrPedido;
        $pedidos->save();
        
        return "Registro modificado";
    }
    public function eliminar(Request $request){
        $pedidos = Pedido::findorFail($request->id);
        $arrPedido = json_decode($pedidos->pedido) ?: [];
        $arrProductos = array();
        foreach($arrPedido as $item){
            if($request->idItem != $item->idPedido){
                array_push($arrProductos,$item);
            }
        }
        $arrPedido = json_encode($arrProductos);
        $pedidos->pedido = $arrPedido;
        $pedidos->save();
        
        return "Registro borrado";
    }
	
	public function pedidoexcel(){
		
        $pedidos = $this->pedidosConCliente()
        ->orderby('pedidos.id','desc')
        ->get();
        $arrRow = [];
        $this->normalizarPedidos($pedidos, true);
        foreach($pedidos as $pedido){
            $productos = $pedido->pedido ?: [];
            foreach($productos as $producto){
				$row = new stdClass();
				$row->pedido_id = $pedido->id;
                $row->nombre = $pedido->nombre;
                $row->apellido = $pedido->apellido;
                $row->razonSocial = $pedido->razonSocial;
                $row->fecha = $pedido->fecha;
                $row->estado = $pedido->estado;
                $row->total = $pedido->total;            
                $row->mensaje = $pedido->mensaje;
                $row->productonombre = $producto->nombre;
                $row->productoprecio = $producto->precio;
                $row->productocantidad = $producto->cantidad;
                array_push($arrRow,$row);
            }
        }
        $pedidos = $arrRow;
        
        
		//dd($pedidos);
        return Excel::download(new PedidoExport($pedidos), 'pedidos.xlsx');
    }
	
	public function facturas(Request $request){
        $estados = ['FACTURADO', 'FACTURADO PENDIENTES'];
        $pedido = $this->aplicarFiltrosFacturas(
            Pedido::with(['cliente', 'obtenerRelacionados'])->whereIn('estado', $estados),
            $request,
            $estados
        )
            ->orderBy('id','desc')
            ->paginate(15)
            ->appends($request->query());
        return view('adm.facturado.facturas',compact('pedido','estados'));
    }
}
