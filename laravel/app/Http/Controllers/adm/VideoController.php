<?php

namespace App\Http\Controllers\adm;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Storage;

class VideoController extends Controller
{  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $videos = Video::orderBy('orden', 'ASC')->get();      
    
       return view('adm.video.contenido', compact('videos'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {   
      $cat = Video::orderBy('orden','ASC')->get();
       return view('adm.video.crear', compact('cat'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       $video = new video;
       $video->orden = $request->orden;
       $video->nombre = $request->nombre;
       $video->descripcion = $request->descripcion;
       $video->link = $request->link;       
       $video->save();

       return redirect()->route('video')->with('success', 'Registro creado');
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
        $video = video::find($id);

       return view('adm.video.editar', compact('video'));
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
       $video = video::find($id);                   
       
       $video->orden     = $request->orden;
       $video->nombre = $request->nombre;
       $video->descripcion = $request->descripcion;
       $video->link = $request->link;
       $video->save();
       return redirect()->route('video')->with('success', "Registro actualizado exitósamente" );
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
       $video = video::find($id);       
       $video->delete();
       return redirect()->back()->with('success', "Registro eliminado exitósamente" );  
   }
}