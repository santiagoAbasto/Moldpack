<?php

namespace App\Http\Controllers;
use App\Models\Contacto;
use App\Models\FacturasRelacion;
use App\Models\Rede;
use App\Models\Pedido;
use App\Models\Empresa;
use App\Models\Video;
use App\Models\Novedad;
use App\Models\NovedadCategoria;
use App\Models\Calidad;
use App\Models\Inicio;
use App\Models\Slider;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\FamiliaProducto;
use App\Models\Logo;
use App\Models\Color;
use App\Models\Descarga;
use App\Models\Contenido;
use App\Models\CarritoContenido;
use App\Models\Cliente;
use App\Models\Metadato;
use App\Models\Service;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMail;
use App\Models\Subcriptores;
use App\Mail\Carrito;
use App\Mail\DondeComprarMail;
use App\Models\PresentacionRelacion;
use stdClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

if (! defined('RECAPTCHA')) {
    define('RECAPTCHA', config('recaptcha.api_secret_key', env('RECAPTCHA_SECRET_KEY', '')));
}

class PageController extends Controller
{
	public function construccion(){
        return view('page.error');
    }
    public function index(){
        //dd($_SERVER["HTTP_HOST"]);
        $active = 'page.inicio';
        $sliders   = Slider::where('seccion', 'inicio')->where('activo','=','1')->orderBy('orden', 'ASC')->get();

        $categorias = Categoria::where('destacado','=',1)->orderBy('orden', 'ASC')->take(5)->get();
        
        $productos = Producto::where('destacado','1')->where('activa','!=','0')->orderBy('orden','ASC')->get();
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $metadatos = Metadato::where('seccion', 'home')->first();
        $novedades = Novedad::where('destacar','=','1')->orderBy('orden', 'ASC')->take(3)->get();
        $inicio = Inicio::first();
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
       return view('page.inicio', compact('sliders', 'logosheader','logosfooter', 'contactos', 'active', 'redes','inicio','categorias','productos','metadatos','novedades'));

    }


    public function empresa(){
        $active = 'page.empresa';        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $empresa = Empresa::find(1);
        $sliders   = Slider::where('seccion', 'empresa')->where('activo','=','1')->orderBy('orden', 'ASC')->get();   
        $inicio = Inicio::first();
        $metadatos = Metadato::where('seccion', 'empresa')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
       return view('page.empresa', compact('logosheader','logosfooter', 'contactos', 'active', 'empresa', 'redes','inicio','metadatos','sliders'));

    }

    public function novedades(){
        $active = 'page.novedades';
        $titulo = 'Novedades';
        $route = 'page.novedad';

        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contenido = Contenido::where('seccion','=','novedad')->first();
        $novedades = Novedad::orderBy('destacar', 'DESC')->orderBy('orden', 'ASC')->get();
        $categorias = NovedadCategoria::orderBy('orden', 'ASC')->get();
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();

       return view('page.novedades', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'novedades','titulo','route','contenido','categorias'));

    }
    public function registro(){
        $active = '';        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $metadatos = Metadato::where('seccion', 'productos')->first();
        return view('page.registro', compact('logosheader','logosfooter', 'contactos', 'active','redes','metadatos'));
    }

    public function registropost(Request $request){                
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $metadatos = Metadato::where('seccion', 'productos')->first();

        $user = new Cliente;
        request()->validate([        
            'email' => 'required|unique:clientes',
        ]);
        $date = date('d/m/y');
        $user->username = $request->email;
        $user->razonSocial = $request->razonSocial;
        $user->password = Hash::make($request->password);
        try { $user->password_encrypted = Crypt::encryptString($request->password); } catch (\Exception $e) {}
        $user->email = $request->email;
        $user->descuento = 0;          
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
		$user->telefono = $request->telefono;
        $user->direccion = $request->direccion;
        $user->direccionEntrega = $request->direccionEntrega;
        $user->dni = $request->dni;
        $user->cuit = $request->cuit;
        $user->estado = 0;
        $user->fechaInicio = $date;
        $user->save();

        return back()->with('success', "Gracias por registrarte. Nos contactaremos a la brevedad");
    }
    public function password(){
        $active = '';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        return view('page.resetpassword', compact('logosheader','logosfooter', 'contactos', 'redes','active'));
    }

    public function passwordpost(Request $request){
        $active = '';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $cliente = Cliente::where('email','=',$request->email)->first();
        if($cliente){
            $html = '<p>Por seguridad no enviamos contraseñas por correo. Por favor comuníquese con Moldpack para solicitar un restablecimiento de acceso.</p>';
            Mail::send([], [], function ($message) use ($html,$cliente) {
                $message->to($cliente->email)
                ->subject('Moldpack - Restablecimiento de contraseña')
                ->setBody($html, 'text/html');
            });
            return back()->with('succes', "Se envio un correo con instrucciones");
        }else{
            return back()->with('error', "Correo no existente");
        }
    }

    public function catalogo (){
        $active = 'page.catalogo';        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $listas = Descarga::orderBy('orden', 'ASC')->get();        
        $redes = Rede::get();
       return view('page.lista', compact('logosheader','logosfooter', 'contactos', 'active', 'redes','listas'));

    }
        public function catalogoView(){        
        $categorias = Categoria::where('activa','!=',0)->orderBy('orden', 'ASC')->get();
        
       return view('page.catalogoView', compact('categorias'));

    }
    public function novedad ($id){
        $active = 'page.novedades';
        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $categorias = NovedadCategoria::orderBy('orden', 'ASC')->get();
        $novedad = Novedad::find($id);
        $contenido = Contenido::where('seccion','=','novedad')->first();
        $redes = Rede::get();
       return view('page.novedad', compact('logosheader','logosfooter', 'contactos', 'active', 'redes','novedad','contenido','categorias'));

    }

    public function productosCategorias(){
        $active = 'page.productos';
        $titulo = 'Categorias';
        $route = 'page.productos';

        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contenido = Contenido::where('seccion','=','categorias')->first();
        $categorias = Categoria::orderBy('orden', 'ASC')->get();        
        $producto = Producto::where('categorias_id','!=',0)->where('activa','!=',0)->orderBy('orden', 'ASC')->get();
        $colores = Color::orderBy('orden', 'ASC')->get();
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $metadatos = Metadato::where('seccion', 'productos')->first();
       return view('page.productosCategorias', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias','titulo','route','metadatos','contenido','producto','colores'));

    }

    public function categorias($id){
        $active = 'page.productos';
        $titulo = 'Categorias';
        $route = 'page.producto';

        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();

        $categorias = Categoria::orderBy('orden', 'ASC')->get();        
        $producto = Producto::where('categorias_id','=',$id)->where('activa','!=',0)->orderBy('orden', 'ASC')->get();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $metadatos = Metadato::where('seccion', 'productos')->first();
       return view('page.categorias', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias','titulo','route','producto','metadatos'));

    }

    public function productos($id){
        $active = 'page.productos';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $producto = FamiliaProducto::find($id);
        $producto = $producto->obtenerProductos;
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $carrito = CarritoContenido::first();
        $metadatos = Metadato::where('seccion', 'productos')->first();
       return view('page.categorias', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias', 'producto','carrito','metadatos'));

    }

    public function producto($id){
        $active = 'page.productos';
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $producto = Producto::find($id);
        
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $carrito = CarritoContenido::first();
        $metadatos = Metadato::where('seccion', 'productos')->first();
       return view('page.producto', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias', 'producto','carrito','metadatos'));

    }

    public function dondeComprar(){
        $active = 'page.comprar';        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();

        $distribuidores = Service::where('seccion','comprar')->get();
        
        $provincias = DB::table('services')
        ->select('provincia')->where('seccion','comprar')->distinct()->get();
        
        $localidades = DB::table('services')
        ->select('localidad')->where('seccion','comprar')->distinct()->get();

        $redes = Rede::get();
       return view('page.comprar', compact('logosheader','logosfooter', 'contactos', 'active','redes','distribuidores','provincias','localidades'));
    }

    public function dondeComprarPost(Request $request){
        
        $active = 'page.comprar';        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();

        $distribuidores = Service::where('seccion','comprar')->get();
        
        if($request->provincia != "0"){            
            $distribuidores = $distribuidores->where('provincia','=',$request->provincia);
        }
        if($request->localidad != "0"){
            $distribuidores = $distribuidores->where('localidad','=',$request->localidad);
        }
        $distribuidores = $distribuidores->values();
        
        $provincias = DB::table('services')
        ->select('provincia')->where('seccion','comprar')->distinct()->get();
        
        $localidades = DB::table('services')
        ->select('localidad')->where('seccion','comprar')->distinct()->get();

        $redes = Rede::get();
        if(count($distribuidores) == 0){
            $distribuidores = Service::where('seccion','comprar')->get();
        }
       return view('page.comprar', compact('logosheader','logosfooter', 'contactos', 'active','redes','distribuidores','provincias','localidades'));
    }

    public function contactos(){
        $active = 'page.contacto';
        
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        //$etiquetas = Etiqueta::orderBy('orden', 'ASC')->get();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $metadatos = Metadato::where('seccion', 'contacto')->first();
       return view('page.contacto', compact('logosheader','logosfooter', 'contactos', 'active', 'redes','metadatos'));

    }

    public function contactosEnviar(Request $request){

        $tokenRecaptcha = $request->token;
        $action = $request->action;
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($cu, CURLOPT_POST, 1);
        curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA, 'response' => $tokenRecaptcha)));
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($cu);
        curl_close($cu);
        
        $datos = json_decode($response,true);
        if($datos['success'] == 1 && $datos['score'] >= 0.5){       
        	$correo = Contacto::select('correo')->first();
        	$dataRequest = $request->all();
        	$file = isset($dataRequest["file"]) ? $request->file('file') : null;
        	Mail::to( $correo->correo )->bcc($request->email)->send( new ContactoMail( $dataRequest , $file ) );		
        	if (count(Mail::failures()) > 0){
            	$respuesta='*Algo salio mal, reintentar mas tarde';
        	}else{
	            $respuesta='*Gracias por comunicarte, te responderemos en la brevedad';
	        }
    	    return $respuesta;
        }else{
            $respuesta='*Algo salio mal, reintentar mas tarde';
        }
    }

    public function subscribirse(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $subscriptor= new Subcriptores($request->only(['email']));
        $subscriptor->save();

        return response()->json(['status' => 'ok']);
    }

    public function buscar(Request $request){
        $active = 'page.productos';
        $titulo = 'Categorias';
        $route = 'page.producto';
        $busqueda = trim((string) $request->input('buscador', $request->input('q', '')));

        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $metadatos = Metadato::where('seccion', 'productos')->first();

        $producto = Producto::with(['obtenerFamilia', 'obtenerSubCategoria', 'obtenerPresentacionRelacionados'])
            ->where('activa','!=',0)
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($subquery) use ($busqueda) {
                    $subquery->where('descripcion', 'LIKE', "%{$busqueda}%")
                        ->orWhere('nombre', 'LIKE', "%{$busqueda}%")
                        ->orWhereHas('obtenerPresentacionRelacionados', function ($presentacion) use ($busqueda) {
                            $presentacion->where('codigo', 'LIKE', "%{$busqueda}%")
                                ->orWhere('presentacion', 'LIKE', "%{$busqueda}%");
                        })
                        ->orWhereHas('obtenerFamilia', function ($categoria) use ($busqueda) {
                            $categoria->where('nombre', 'LIKE', "%{$busqueda}%");
                        })
                        ->orWhereHas('obtenerSubCategoria', function ($familia) use ($busqueda) {
                            $familia->where('nombre', 'LIKE', "%{$busqueda}%");
                        });
                });
            })
            ->orderBy('orden', 'ASC')
            ->get()
            ->unique('id')
            ->values();
        
        return view('page.categorias', compact('logosheader','logosfooter', 'contactos', 'active','redes', 'categorias','titulo','route','producto','metadatos'));                
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

}
