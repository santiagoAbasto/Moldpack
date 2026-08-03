<?php

namespace App\Http\Controllers;

use App\Mail\Presupuesto;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Rede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PresupuestoController extends Controller
{
    public function vistaPresupuesto(){
        $active = 'page.presupuesto';
        $contactos=Contacto::all();
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();
        $redes = Rede::get();
        return view('page.presupuesto',compact('contactos','logosheader', 'logosfooter', 'active','redes'));
        
    
    }
    public function presupuesto( Request $request ) {
        
       
         $dataRequest = $request->all();
        
        $file = isset($dataRequest["file"]) ? $request->file('file') : null;
        
            Mail::to( 'canterosdiego@outlook.com' )->send( new Presupuesto( $dataRequest , $file ) );
            $obj= new \stdClass();
            if (count(Mail::failures()) > 0){
                $obj->respuesta='*Error al enviar E-mail.';
            }else{
                $obj->respuesta='*E-mail enviado. Nos contacteremos en la brevedad. Gracias!';
            }
            $active = 'page.presupuesto';
            $contactos=Contacto::all();
            $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
            $logosfooter = Logo::where('seccion', 'footer')->first();
            $redes = Rede::get();
            return view('page.presupuesto',compact('contactos','logosheader', 'logosfooter', 'active','redes','obj'));
            
               
       
    }
}
