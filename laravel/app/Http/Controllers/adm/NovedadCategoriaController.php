<?php

namespace App\Http\Controllers\adm;
use App\Http\Controllers\Controller;
use App\Models\NovedadCategoria;
use App\Models\Contenido;
use Illuminate\Http\Request;
use Storage;

class NovedadCategoriaController extends Controller
{  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $novedadCategoria = NovedadCategoria::orderBy('orden', 'ASC')->get();
      $contenido = Contenido::where('seccion','=','novedad')->first();
      
      if(!$contenido){
        $contenido = new Contenido();
        $contenido->seccion = "novedad";
        $contenido->save();
      }
       return view('adm.novedadCategorias.contenido', compact('novedadCategoria','contenido'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {   
      
       return view('adm.novedadCategorias.crear');
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       $novedadCategoria = new NovedadCategoria;
       $novedadCategoria->orden = $request->orden;
       $novedadCategoria->nombre = $request->nombre;       
       $novedadCategoria->save();


       return redirect()->route('novedadCategoria')->with('success', 'La aplicacio fue creado');
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
        $novedadCategoria = NovedadCategoria::find($id);
        

       return view('adm.novedadCategorias.editar', compact('novedadCategoria'));
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
       $novedadCategoria = NovedadCategoria::find($id);
       $novedadCategoria->orden     = $request->orden;
       $novedadCategoria->nombre = $request->nombre;       
       $novedadCategoria->save();
       return redirect()->route('novedadCategoria')->with('success', "Registro actualizado exitósamente" );
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
       $novedadCategoria = NovedadCategoria::find($id);
       storage::delete($novedadCategoria->imagen);
       $novedadCategoria->delete();
       return redirect()->back()->with('success', "Registro eliminado exitósamente" );  
   }
}