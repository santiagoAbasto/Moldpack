<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Descarga;
use App\Models\Descarga_zp;
use App\Models\Descarga_categoria;
use Illuminate\Support\Facades\App;

class DescargasController extends Controller
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

    public function descargas(){
        
        $descargas = Descarga::all();
        $categorias = Descarga_categoria::all();

        return view('adm.form_descargas',compact('descargas','categorias'));
    }

    public function descargas_put(Request $request, $id){

        $descargas = Descarga::findorFail($id);

        if($request->titulo == null){
            $descargas->titulo = "";
        }else{
            $descargas->titulo = $request->titulo;    
        }
        
        $date = date("dmyGis");
        
        if(isset($request->archivo)){                           
                $archivo = $request->archivo;
                $nombre = $date.$archivo->getClientOriginalName();
                $ext = explode(".", $nombre);
                $nombre = "archivo_".$date;
                $nombre.= ".".$ext[1];
                $archivo->move('img',$nombre);
                $descargas->archivo = $nombre;                   
        }else{
            $descargas->archivo = $descargas->archivo;   
        }

        if(isset($request->imagen)){                           
                $imagen = $request->imagen;
                $nombre = $date.$imagen->getClientOriginalName();
                $ext = explode(".", $nombre);
                $nombre = "img_".$date;
                $nombre.= ".".$ext[1];
                $imagen->move('img',$nombre);
                $descargas->imagen = $nombre;                   
        }else{
            $descargas->imagen = $descargas->imagen;   
        }

        $descargas->save();

        return redirect()->route('descargas');
    }

    public function descargas_post(Request $request){       
        $descargas = new Descarga;

        if($request->titulo == null){
            $descargas->titulo = $descargas->titulo;
        }else{
        $descargas->titulo = $request->titulo;    
        }

        $date = date("dmyGis");

        if(isset($request->archivo)){                           
            $archivo = $request->archivo;
            $nombre = $date.$archivo->getClientOriginalName();
            $ext = explode(".", $nombre);
            $nombre = $date;
            $nombre.= ".".$ext[1];
            $archivo->move('img',$nombre);
            $descargas->archivo = $nombre;                   
        }else{
            $descargas->archivo = "";   
        }

        if(isset($request->imagen)){                           
            $imagen = $request->imagen;
            $nombre = $date.$imagen->getClientOriginalName();
            $ext = explode(".", $nombre);
            $nombre = "img_".$date;
            $nombre.= ".".$ext[1];
            $imagen->move('img',$nombre);
            $descargas->imagen = $nombre;                   
        }else{
            $descargas->imagen = "";   
        }

        $descargas->save();
    
        return redirect()->route('descargas');     
    }
    public function descargas_delete(Request $request, $id){

        $descargas = Descarga::findorFail($id);
        $descargas->delete();

        return redirect()->route('descargas');
    }

    public function descargas_categorias(){

        $contenido = Descarga_categoria::all();
        
        return view('adm.form_descargas_categorias',compact('contenido'));
    }

    public function descargas_categorias_post(Request $request){

        $descargas_categoria = new Descarga_categoria;
        $descargas_categoria->nombre = $request->nombre;
        $descargas_categoria->save();

        return redirect()->route('descargas_categorias');
    }
    public function descargas_categorias_put(Request $request, $id){

        $descargas_categoria = Descarga_categoria::findorFail($id);
        $descargas_categoria->nombre = $request->nombre;
        $descargas_categoria->save();

        return redirect()->route('descargas_categorias');
    }
    public function descargas_categorias_delete(Request $request, $id){
        $descargas_categoria= Descarga_categoria::findorFail($id);
        $descargas_categoria->delete();

        return redirect()->route('descargas_categorias');
    }
    public function descargas_zp(){
        
        $descargas = Descarga_zp::all();   

        return view('adm.form_descargas_zp',compact('descargas'));
    }

    public function descargas_zp_put(Request $request, $id){

        $descargas = Descarga_zp::findorFail($id);        

        if($request->titulo == null){
            $descargas->titulo = "";
        }else{
            $descargas->titulo = $request->titulo;    
        }
        
        $date = date("dmyGis");
        
        if(isset($request->archivo)){                           
                $archivo = $request->archivo;
                $nombre = $date.$archivo->getClientOriginalName();
                $ext = explode(".", $nombre);
                $nombre = "archivo_".$date;
                $nombre.= ".".$ext[1];
                $archivo->move('img',$nombre);
                $descargas->archivo = $nombre;                   
        }else{
            $descargas->archivo = $descargas->archivo;   
        }

        if(isset($request->imagen)){                           
                $imagen = $request->imagen;
                $nombre = $date.$imagen->getClientOriginalName();
                $ext = explode(".", $nombre);
                $nombre = "img_".$date;
                $nombre.= ".".$ext[1];
                $imagen->move('img',$nombre);
                $descargas->imagen = $nombre;                   
        }else{
            $descargas->imagen = $descargas->imagen;   
        }

        $descargas->save();

        return redirect()->route('form_descargas_zp');
    }

    public function descargas_zp_post(Request $request){       
        $descargas = new Descarga_zp;

        if($request->titulo == null){
            $descargas->titulo = $descargas->titulo;
        }else{
        $descargas->titulo = $request->titulo;    
        }

        $date = date("dmyGis");

        if(isset($request->archivo)){                           
            $archivo = $request->archivo;
            $nombre = $date.$archivo->getClientOriginalName();
            $ext = explode(".", $nombre);
            $nombre = $date;
            $nombre.= ".".$ext[1];
            $archivo->move('img',$nombre);
            $descargas->archivo = $nombre;                   
        }else{
            $descargas->archivo = "";   
        }

        if(isset($request->imagen)){                           
            $imagen = $request->imagen;
            $nombre = $date.$imagen->getClientOriginalName();
            $ext = explode(".", $nombre);
            $nombre = "img_".$date;
            $nombre.= ".".$ext[1];
            $imagen->move('img',$nombre);
            $descargas->imagen = $nombre;                   
        }else{
            $descargas->imagen = "";   
        }

        $descargas->save();
    
        return redirect()->route('form_descargas_zp');     
    }
    public function descargas_zp_delete(Request $request, $id){

        $descargas_zp = Descarga_zp::findorFail($id);
        $descargas_zp->delete();

        return redirect()->route('form_descargas_zp');
    }
}
