<?php

namespace App\Http\Controllers\adm;
use App\Http\Controllers\Controller;
use App\Models\Novedad;
use App\Models\Contenido;
use App\Models\NovedadCategoria;
use Illuminate\Http\Request;
use Storage;

class NovedadController extends Controller
{  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $novedades = Novedad::orderBy('orden', 'ASC')->get();
       return view('adm.novedades.contenido', compact('novedades'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {   
      $cat = NovedadCategoria::orderBy('orden','ASC')->get();
       return view('adm.novedades.crear', compact('cat'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       $novedades = new Novedad;
       $novedades->orden = $request->orden;
       $novedades->nombre = $request->nombre;
       $novedades->descripcion = $request->descripcion;
       $novedades->descripcion2 = $request->descripcion2;
       $novedades->categoria = $request->categoria;       
       $novedades->imagen = $request->file('imagen')->store('public/novedades');
       if($request->destacar){
            $novedades->destacar= $request->destacar;
        }
       $novedades->save();


       return redirect()->route('novedad')->with('success', 'La aplicacio fue creado');
   }

   public function imgnovedad(Request $request)
   {
       $contenido = Contenido::where('seccion','=','novedad')->first();

       if(!$contenido){        
           $contenido = new Contenido();            
       }
       if($request->file('imagen') === null){
           
       }else{
           $contenido->imagen = $request->file('imagen')->store('public/novedad');
       }
       $contenido->seccion = "novedad";
       $contenido->save();
             
       
       return redirect()->route('novedadCategoria')->with('success', 'Registro guardado');
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
        $novedades = Novedad::find($id);
        $cat = NovedadCategoria::orderBy('orden','ASC')->get();

       return view('adm.novedades.editar', compact('novedades','cat'));
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
       $novedades = Novedad::find($id);      
       
       if ($request->hasFile('imagen'))
       {
           Storage::delete($novedades->imagen);
           $path = $request->file('imagen')->store('public/novedades');
       }else{
           $path = $novedades->imagen;
       }

       if($request->destacar){
            $novedades->destacar= $request->destacar;
        }
      
       $novedades->imagen    = $path;
       $novedades->orden     = $request->orden;
       $novedades->nombre = $request->nombre;
       $novedades->descripcion = $request->descripcion;
       $novedades->descripcion2 = $request->descripcion2;
       $novedades->categoria = $request->categoria;
       $novedades->save();
       return redirect()->route('novedad')->with('success', "Registro actualizado exitósamente" );
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
       $novedades = Novedad::find($id);
       storage::delete($novedades->imagen);
       $novedades->delete();
       return redirect()->back()->with('success', "Registro eliminado exitósamente" );  
   }
}