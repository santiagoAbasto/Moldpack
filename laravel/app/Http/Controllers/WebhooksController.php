<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use App\Models\CarritoContenido;
use App\Models\Logo;
use App\Models\Rede;
use App\Models\Producto;
use App\Models\Pedido;
use stdClass;
use App\Mail\Carrito;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class WebhooksController extends Controller
{
  public function __invoke(Request $request)
  {

    $contacto = Contacto::first();
    $carrito = CarritoContenido::first();
    $active = 'page.carrito';        
    $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
    $logosfooter = Logo::where('seccion', 'footer')->first();       
    
    $contactos = Contacto::orderBy('orden', 'ASC')->get();
    $redes = Rede::get(); 

    
    
    return view('ZonaPrivada.carrito_fin',compact('contactos','carrito','active','logosheader','logosfooter','redes'));
  }
  public function webhooks(Request $request,$ids)
  {    
    $arr_productos = [];
    $arrIds = explode('-',$ids);
    $envio = "";
    $string = "";
    $total = 0;
    foreach($arrIds as $id){
        
        $prodId = explode('_',$id);
        if(isset($prodId[1]) && $prodId[0] == 'envio'){
        $envio = $prodId[1];
        }
        $prod = Producto::find($prodId[0]);              
        if($prod){                  
                $producto = new stdClass;
                $producto->nombre = $prod->obtenerCategoria->obtenerProductoCategoria->nombre." ".$prod->obtenerCategoria->nombre;
                $producto->codigo = $prod->codigo;                
                $producto->precio = $prod->precio;
                $producto->id = $prod->id;
                $producto->cantidad = $prodId[1];
                array_push($arr_productos,$producto);
                $total += floatval($prod->precio)*intval($prodId[1]);
                $string .="Producto: ".$prod->codigo." / ".$prod->descripcion."  / cant: ".$prodId[1]." / $ ".$prod->precio."----";
        }
    } 
    
    
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

    $carrito_pedido = json_encode($arr_productos);

    $costoEnvio = 0;

    if($envio == "Opcion 1(Hasta Av Marquez)"){
        $costoEnvio = $carrito->costo;
    }

    if($envio == "Opcion 2(Av Marquez)"){
        $costoEnvio = $carrito->costo2;
    }

    if($envio == "Envios al interior"){
        $costoEnvio = $carrito->costo3;
    }
    $iva = $carrito->iva;
    $iva = $iva / 100;
    $iva = $iva + 1;
    
    $totalIva = $total*$iva;
    $resto = $totalIva-$total;

    $ingresosbrutos = Auth::guard('cliente')->user()->ingresosbrutos;
    $ingresosbrutos = $ingresosbrutos / 100;
    $ingresosbrutos = $ingresosbrutos + 1;

    $totalBruto = $totalIva*$ingresosbrutos;
    $restoBruto = $totalBruto-$totalIva;

    $total = $total+$resto+$restoBruto+$costoEnvio;

        $pedido->usuario_id = Auth::guard('cliente')->user()->id;
        $pedido->total = $request->total_pedido;
        $pedido->pedido = $carrito_pedido;
        $pedido->total = "$ ".$total;

        $pedido_carrito =new stdClass;        
        $pedido_carrito->mensaje = "";
        $pedido_carrito->pedido = $string;
        $pedido_carrito->orden = "";
        $pedido_carrito->entrega = $envio;
        $pedido_carrito->pago = "PAGO POR MERCADO PAGO";
        $pedido_carrito->nombre = Auth::guard('cliente')->user()->nombre;
        $pedido_carrito->dni = Auth::guard('cliente')->user()->dni;
        $pedido_carrito->email = Auth::guard('cliente')->user()->email;
        $pedido_carrito->celular = Auth::guard('cliente')->user()->celular;
        $pedido_carrito->direccion = Auth::guard('cliente')->user()->direccion;
        $pedido_carrito->localidad = Auth::guard('cliente')->user()->localidad;
        $pedido_carrito->provincia = Auth::guard('cliente')->user()->provincia;
        $pedido_carrito->cp = Auth::guard('cliente')->user()->cp;
        $pedido_carrito->totalCarrito = Auth::guard('cliente')->user()->totalCarrito;        
        $pedido_carrito->otro = "";
        $pedido_carrito->total_pedido = $total;
        
        $file = $request->file('file') !== null ? $request->file('file') : null;
        $email = new Carrito($pedido_carrito,$file);
        Mail::to($contacto->correo)
          ->bcc([
              'pmathey@moldpack.com.ar', 
              'hmathey@moldpack.com.ar', 
              'ventas@moldpack.com.ar', 
              'compras@moldpack.com.ar', 
              Auth::guard('cliente')->user()->email])
        	->send($email);
        $pedido->save();


    return view('ZonaPrivada.carrito_fin',compact('contactos','carrito','active','logosheader','logosfooter','redes'));
  }
}
