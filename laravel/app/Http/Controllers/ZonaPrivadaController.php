<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Descarga;
use App\Models\Color;
use App\Models\Contacto;
use App\Models\Herraje;
use App\Models\Pedido;
use App\Models\CarritoAbandonado;
use App\Models\CarritoContenido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\FamiliaProducto;
use App\Models\Aplicacion;
use App\Mail\Carrito;
use stdClass;
use Http;
use App\Models\Rede;
use App\Models\Logo;
use App\Models\PresentacionRelacion;
use Illuminate\Support\Facades\App;

class ZonaPrivadaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.cliente');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function pedido(){
        $active = 'page.pedido';

        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        
        $categorias = Categoria::orderBy('orden', 'ASC')->where('activa','!=',0)->get();
        $productos = Producto::orderBy('categorias_id', 'ASC')->orderBy('subcategorias_id', 'ASC')->orderBy('orden', 'ASC')->where('activa','!=',0)->paginate(12);        
        
        $carrito = CarritoContenido::first();
        $buscador = null;
       return view('ZonaPrivada.productoPedido', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias', 'productos','carrito','buscador'));
    }

    public function categoriasPedido($id){
        $active = 'page.productos';
        $titulo = 'Categorias';
        $route = 'page.pedido';
        $carrito = CarritoContenido::first();
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();

        $categorias = Categoria::orderBy('orden', 'ASC')->get();        
        $productos = FamiliaProducto::where('categorias_id','=',$id)->orderBy('orden', 'ASC')->get();
        
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();        
        return view('ZonaPrivada.productoPedido', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias', 'productos','carrito'));
    }

    public function productoPedido($id){
        $active = 'page.pedido';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $productos = FamiliaProducto::find($id);
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $carrito = CarritoContenido::first();

       return view('ZonaPrivada.productoPedido', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias', 'productos','carrito'));
    }

    public function carrito(){
        $active = 'page.carrito';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $carrito = CarritoContenido::first();

       return view('ZonaPrivada.carrito', compact('logosheader','logosfooter', 'contactos', 'active','redes','carrito'));

    }

    public function obtenerCarritoAbandonado()
    {
        $clienteId = Auth::guard('cliente')->id();

        $carrito = CarritoAbandonado::where('cliente_id', $clienteId)
            ->whereNull('completed_at')
            ->where('items_count', '>', 0)
            ->orderByDesc('last_activity_at')
            ->first();

        if (!$carrito) {
            return response()->json(['ok' => true, 'items' => []]);
        }

        return response()->json([
            'ok' => true,
            'items' => $carrito->items ?: [],
            'last_activity_at' => optional($carrito->last_activity_at)->toDateTimeString(),
        ]);
    }

    public function guardarCarritoAbandonado(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();
        $items = $request->input('items', []);

        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }

        if (!is_array($items)) {
            $items = [];
        }

        $itemsNormalizados = [];
        $cantidadTotal = 0;
        $totalEstimado = 0;

        foreach ($items as $item) {
            if (is_object($item)) {
                $item = (array) $item;
            }

            if (!is_array($item)) {
                continue;
            }

            $nombre = trim((string) ($item['nombre'] ?? ''));
            $codigo = trim((string) ($item['codigo'] ?? ''));
            $presentacion = trim((string) ($item['presentacion'] ?? ''));
            $cantidad = max(1, (int) ($item['cantidad'] ?? 1));
            $precio = $this->normalizarNumeroCarrito($item['precio'] ?? 0);
            $subtotal = $this->normalizarNumeroCarrito($item['subtotal'] ?? 0);

            if ($subtotal <= 0 && $precio > 0) {
                $subtotal = $precio * $cantidad;
            }

            if ($nombre === '' && $codigo === '' && empty($item['productoid']) && empty($item['producto'])) {
                continue;
            }

            $itemsNormalizados[] = [
                'codigo' => $codigo,
                'nombre' => $nombre,
                'presentacion' => $presentacion,
                'cantidad' => $cantidad,
                'precio' => round($precio, 2),
                'subtotal' => round($subtotal, 2),
                'productoid' => $item['productoid'] ?? ($item['producto'] ?? null),
                'presentacionid' => $item['presentacionid'] ?? null,
            ];

            $cantidadTotal += $cantidad;
            $totalEstimado += $subtotal;
        }

        if ($cantidadTotal <= 0) {
            $this->marcarCarritoAbandonadoCompletado();

            return response()->json(['ok' => true, 'cleared' => true]);
        }

        CarritoAbandonado::updateOrCreate(
            ['cliente_id' => $cliente->id],
            [
                'email' => $cliente->email,
                'items' => $itemsNormalizados,
                'items_count' => $cantidadTotal,
                'total_estimado' => round($totalEstimado, 2),
                'last_activity_at' => now(),
                'reminder_sent_at' => null,
                'completed_at' => null,
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function carrito_post(Request $request){
        $contacto = Contacto::first();
        $carrito = CarritoContenido::first();

        $pedido = new Pedido;
        $pedido->fecha = date('d/m/o');
        $pedido->estado = 'pendiente';

        $arr_carrito = [];
        $string = "";
        $total = 0;
        $carritoPedido = json_decode($request->producto, true);
        // Normaliza a array siempre
        if (!is_array($carritoPedido)) {
            $carritoPedido = [$carritoPedido];
        }
        foreach ($carritoPedido as $i => $item) {
            // Si el item es null, salta
            if (!$item) continue;
            $imagen = $item['imagen'] ?? '';
            $cantidad = max(1, intval($item['cantidad'] ?? 1));
            $presentacionid = $item['presentacionid'] ?? null;
            $productoid = $item['productoid'] ?? null;
            $prod = Producto::where('id', '=', $productoid)->first();
            if ($prod) {
                $presentacionObj = PresentacionRelacion::find($presentacionid);
                if (!$presentacionObj || intval($presentacionObj->producto_id) !== intval($prod->id)) {
                    continue;
                }

                $codigoPresentacion = $presentacionObj->codigo;
                $precioPedido = round(floatval($presentacionObj->precio), 2);
                $producto = new stdClass;
                $producto->imagen = $imagen;
                $producto->codigo = $codigoPresentacion; // Código de la presentación (SKU)
                $producto->nombre = $prod->nombre . " " . $presentacionObj->presentacion;
                $producto->precio = $precioPedido;
                $producto->precio_congelado = $precioPedido;
                $producto->presentacionid = $presentacionid;
                $producto->stock = $presentacionObj->stock;
                $producto->id = $prod->id;
                $producto->cantidad = $cantidad;
                $producto->cantidad_original_cliente = $cantidad;
                $producto->cantidad_preparada_logistica = $cantidad;
                $producto->cantidad_modificada_logistica = 0;
                $producto->idPedido = $i;
                $arr_carrito[] = $producto;
                $total += $precioPedido * intval($cantidad);
                $string .= "Producto: " . $prod->nombre . " / Codigo: " . $codigoPresentacion . " / Presentacion: " . $presentacionObj->presentacion . "  / cant: " . $cantidad . " / " . $precioPedido . "----";
            }
        }
        $descuento = 1;
        $descuentoCarrito = 0;
        if(Auth::guard('cliente')->user()->descuento != 0 || Auth::guard('cliente')->user()->descuento != null){
            $descuentoCarrito = Auth::guard('cliente')->user()->descuento;
            $descuento = 100 - Auth::guard('cliente')->user()->descuento;
            $descuento = $descuento / 100;
        }
        $totalDescuento = $total*$descuento;        

        $iva = $carrito->iva;
        $iva = $iva / 100;
        $iva = $iva + 1;
        
        $totalIva = $totalDescuento*$iva;
        
        $carrito_pedido = json_encode($arr_carrito);
        $pedido->usuario_id = Auth::guard('cliente')->user()->id;
        $pedido->total = $totalIva;
        $pedido->pedido = $carrito_pedido;
        $pedido->mensaje = $request->obeservacion;
        $pedido->save();
        $this->marcarCarritoAbandonadoCompletado();
        
        $pedido_carrito =new stdClass;        
        $pedido_carrito->mensaje = $request->obeservacion;
        $coleccion = collect($arr_carrito);
        $coleccion = $coleccion->sortBy([['codigo', SORT_NATURAL],['precio','asc']]);
        $arr_carrito = $coleccion->values()->all();
        
        $pedido_carrito->pedido = $arr_carrito;
        $pedido_carrito->usario = Auth::guard('cliente')->user();
        $pedido_carrito->numeroPedido = $pedido->id;
        $pedido_carrito->entrega = $request->entrega;
        $pedido_carrito->entregatext = "";
        $pedido_carrito->descuentoCarrito = $descuentoCarrito;
        if($request->entregaconvenir){
            $pedido_carrito->entregatext = $request->entregaconvenir;
        }
        $pedido_carrito->email = Auth::guard('cliente')->user()->email;
        $pedido_carrito->total_pedido = $totalIva;
        $pedido_carrito->total_sin_descuento = $total;
        $pedido_carrito->total_con_descuento = $totalDescuento;
        $pedido_carrito->porcentaje_iva = $carrito->iva;
        $pedido_carrito->monto_iva = $totalIva - $totalDescuento;
        
        $file = $request->file('file') ?: $request->file('archivo');
        $email = new Carrito($pedido_carrito,$file);
        
        $contacto = Contacto::first();        
        try {
		    // Enviar correo
    		Mail::to($contacto->correo)
                ->bcc([
                    'pmathey@moldpack.com.ar', 
                    'hmathey@moldpack.com.ar', 
                    'ventas@moldpack.com.ar', 
                    'compras@moldpack.com.ar', 
                    Auth::guard('cliente')->user()->email
                    ])
                ->send($email);

    		// Si llegamos aquí, el correo se envió correctamente
    		$respuesta = 'Muchas gracias por su compra. En breve le llegará un e-mail con la confirmación del pedido.';
		} catch (\Exception $e) {
    		// Manejo de la excepción en caso de que el envío del correo falle
    		\Log::error('Error al enviar correo: ' . $e->getMessage());
    		\Log::error($contacto->correo . ' - ' . Auth::guard('cliente')->user()->email);    
    		$respuesta = '*Algo salió mal, reintentar más tarde';
		}

        return $respuesta;
        
    }

    public function comprar(Request $request){
                
        $contacto = Contacto::first();
        $carrito = CarritoContenido::first();
        $active = 'page.carrito';        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();       
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get(); 
        
        $pedido = new Pedido;
        $pedido->fecha = date('d/m/o');
        $pedido->estado = 'pendiente';        
        
        $arr_carrito = [];
        
        $total = $request->total_pedido;
        $string = "";
        
        for($i=0; $i < count($request->producto); $i++){
            $prod = Producto::find($request->producto[$i]);
            if($prod){
                $carritoEmail = new stdClass;
                $carritoEmail->nombre = $prod->obtenerCategoria->obtenerProductoCategoria->nombre." ".$prod->obtenerCategoria->nombre;
                $carritoEmail->codigo = $prod->codigo;                
                $precioPedido = round(floatval($prod->precio), 2);
                $carritoEmail->precio = $precioPedido;
                $carritoEmail->precio_congelado = $precioPedido;
                $carritoEmail->id = $prod->id;
                $cantidadPedido = max(1, intval($request->cantidad[$i] ?? 1));
                $carritoEmail->cantidad = $cantidadPedido;
                $carritoEmail->cantidad_original_cliente = $cantidadPedido;
                $carritoEmail->cantidad_preparada_logistica = $cantidadPedido;
                $carritoEmail->cantidad_modificada_logistica = 0;
                $carritoEmail->idPedido = $i;
                array_push($arr_carrito,$carritoEmail);
                $total += $precioPedido * $cantidadPedido;
                $string .="Producto: ".$prod->codigo." / ".$prod->descripcion."  / cant".$cantidadPedido." / ".$precioPedido."----";
            }            
        }         
        $carrito_pedido = json_encode($arr_carrito);
        $pedido->usuario_id = Auth::guard('cliente')->user()->id;
        $pedido->total = $total;
        $pedido->pedido = $carrito_pedido;
        $pedido->mensaje = $request->msj;

        $pedido_carrito =new stdClass;        
        $pedido_carrito->mensaje = $request->msj;
        $pedido_carrito->pedido = $string;
        $pedido_carrito->orden = "";
        $pedido_carrito->entrega = $request->envio;
        $pedido_carrito->pago = $request->pago;
        $pedido_carrito->nombre = $request->nombre;
        $pedido_carrito->dni = $request->dni;
        $pedido_carrito->email = $request->email;
        $pedido_carrito->celular = $request->celular;
        $pedido_carrito->direccion = $request->direccion;
        $pedido_carrito->localidad = $request->localidad;
        $pedido_carrito->provincia = $request->provincia;
        $pedido_carrito->cp = $request->cp;
        $pedido_carrito->totalCarrito = $request->totalCarrito;
        $otro = "No";
        if($request->otro){
            $otro = "Si";
        }
        $pedido_carrito->otro = $otro;
        $pedido_carrito->total_pedido = $total;
        
        $file = $request->file('file') !== null ? $request->file('file') : null;
        $email = new Carrito($pedido_carrito,$file);
        
        $contacto = Contacto::first();        
        $corre_contacto = $contacto->correo;
        
        Mail::to($contacto->correo)
            ->bcc([
                'pmathey@moldpack.com.ar', 
                'hmathey@moldpack.com.ar', 
                'ventas@moldpack.com.ar', 
                'compras@moldpack.com.ar', 
                Auth::guard('cliente')->user()->email])
            ->send($email);
        $pedido->save();
        $this->marcarCarritoAbandonadoCompletado();
        
        return view('ZonaPrivada.carrito_fin',compact('contactos','carrito','active','logosheader','logosfooter','redes'));
    }

    private function normalizarNumeroCarrito($value)
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = preg_replace('/[^\d,.\-]/', '', (string) $value);

        if (strpos($value, ',') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        return (float) $value;
    }

    private function marcarCarritoAbandonadoCompletado()
    {
        $clienteId = Auth::guard('cliente')->id();

        if (!$clienteId) {
            return;
        }

        CarritoAbandonado::where('cliente_id', $clienteId)
            ->whereNull('completed_at')
            ->update(['completed_at' => now()]);
    }

    public function historico(Request $request, $id_cliente)
    {
        $active = 'page.historico';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();               
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get(); 
        
        $id = Auth::guard('cliente')->user()->id;
        
        $pedido = DB::table('pedidos')->where('usuario_id','=',$id)->orderBy('id','desc')->get();
        
        foreach ($pedido as $item) {
            $productos = json_decode($item->pedido);    
            $item->pedido = $productos;
        }
        return view('ZonaPrivada.historico',compact('contactos','active','logosheader','logosfooter','redes','pedido'));
    }

    public function facturas(Request $request)
    {
        $active = 'page.facturas';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();               
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        
        $id = Auth::guard('cliente')->user()->id;        
        $busqueda = trim((string) $request->query('q', ''));
        
        $pedido = Pedido::with('obtenerRelacionados')
            ->where('usuario_id','=',$id)
            ->whereIn('estado', ['FACTURADO', 'FACTURADO PENDIENTES'])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($subquery) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $subquery->orWhere('id', (int) $busqueda);
                    }

                    $subquery->orWhere('fecha', 'LIKE', "%{$busqueda}%")
                        ->orWhere('estado', 'LIKE', "%{$busqueda}%")
                        ->orWhere('pedido', 'LIKE', "%{$busqueda}%")
                        ->orWhereHas('obtenerRelacionados', function ($factura) use ($busqueda) {
                            $factura->where('numeroFactura', 'LIKE', "%{$busqueda}%")
                                ->orWhere('relacion_id', 'LIKE', "%{$busqueda}%")
                                ->orWhere('factura', 'LIKE', "%{$busqueda}%")
                                ->orWhere('estado', 'LIKE', "%{$busqueda}%");
                        });
                });
            })
            ->orderBy('id','desc')
            ->paginate(15)
            ->appends($request->query());
        
        return view('ZonaPrivada.facturas',compact('contactos','active','logosheader','logosfooter','redes','pedido'));
    }

    

    
    public function buscar(Request $request){        
        $active = 'page.pedido';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $producto = trim((string) $request->input('producto', ''));
        $codigo = trim((string) $request->input('codigo', ''));
        $categoria = $request->input('categoria');
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $carrito = CarritoContenido::first();

        $productos = Producto::with('obtenerPresentacionRelacionados')
            ->where('activa','!=',0)
            ->when($producto !== '', function ($query) use ($producto) {
                $query->where(function ($subquery) use ($producto) {
                    $subquery->where('nombre', 'LIKE', "%{$producto}%")
                        ->orWhere('descripcion', 'LIKE', "%{$producto}%");
                });
            })
            ->when($categoria !== null && $categoria !== '' && (string) $categoria !== '0', function ($query) use ($categoria) {
                $query->where('categorias_id', $categoria);
            })
            ->when($codigo !== '', function ($query) use ($codigo) {
                $query->where(function ($subquery) use ($codigo) {
                    $subquery->where('codigo', 'LIKE', "%{$codigo}%")
                        ->orWhereHas('obtenerPresentacionRelacionados', function ($presentacion) use ($codigo) {
                            $presentacion->where('codigo', 'LIKE', "%{$codigo}%");
                        });
                });
            })
            ->orderBy('categorias_id', 'ASC')
            ->orderBy('subcategorias_id', 'ASC')
            ->orderBy('orden', 'ASC')
            ->get()
            ->unique('id')
            ->values();
        
        $buscador = 1;
        return view('ZonaPrivada.productoPedidoSearch', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias', 'productos','carrito','buscador'));
    }
    public function recomprar(Request $request){        

        $pedido = Pedido::where('id', $request->id)
            ->where('usuario_id', Auth::guard('cliente')->user()->id)
            ->firstOrFail();
        $arrPedido = $pedido->pedido;
        $arrPedido = json_decode($arrPedido);
        $arrProducts = [];
        foreach($arrPedido as $item){
            $presentacion = PresentacionRelacion::find($item->presentacionid);
			if($presentacion){			
            $precio = floatval($presentacion->precio);
            $prodAux = new StdClass;
            $prodAux->cantidad = $item->cantidad;
            $prodAux->codigo = $presentacion->codigo;
			$prodAux->imagen = "";
			if(isset($item->imagen)){
				$prodAux->imagen = $item->imagen;
			}            
            $prodAux->nombre = $item->nombre;
            $prodAux->presentacion = $presentacion->presentacion;
            $prodAux->stock = $presentacion->stock;
			$prodAux->productoid=0;
			if(isset($item->id)){
			$prodAux->productoid = $item->id;	
			}
            $prodAux->presentacionid = $presentacion->id;
            $prodAux->precio = round($presentacion->precio,2);
            $subtotal = intVal($item->cantidad) * floatVal($presentacion->precio);
            $prodAux->subtotal = round($subtotal,2);
            array_push($arrProducts,$prodAux);	
			}
        }
       

        return $arrProducts;
    }

    public function lista(){
        $active = 'page.lista';        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $listas = Descarga::orderBy('orden', 'ASC')->get();

        $redes = Rede::get();
        return view('ZonaPrivada.lista', compact('logosheader','logosfooter', 'contactos', 'active','redes','listas'));
    }
}
