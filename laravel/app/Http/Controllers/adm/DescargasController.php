<?php

namespace App\Http\Controllers\adm;
use App\Models\Descarga;
use App\Models\Lista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DescargasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $descargas = Descarga::orderBy('orden', 'ASC')->get();        
        
        return view('adm.descargas.contenido', compact('descargas'));
    }

    public function listas()
    {
        $listas = Lista::orderBy('orden', 'ASC')->get();        
        
        return view('adm.listas.contenido', compact('listas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $descargas  = Descarga::orderBy('orden', 'ASC')->get();        
        
        return view('adm.descargas.crear', compact('descargas'));
    }

    public function listacreate()
    {   
        $listas  = Lista::orderBy('orden', 'ASC')->get();        
        
        return view('adm.listas.crear', compact('listas'));
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $descarga = new Descarga;
        $descarga->orden = $request->orden;
        $descarga->titulo = $request->titulo;
        if($request->file('imagen') === null){
            $descarga->imagen = $descarga->imagen;
        }else{
            $descarga->imagen = $request->file('imagen')->store('public/descargas');
        }
        if($request->file('archivo') === null){
            $descarga->archivo = $descarga->archivo;
        }else{
            $descarga->archivo = $request->file('archivo')->store('public/descargas');        
        }
        $descarga->save();
        return redirect()->route('deescarga')->with('success', 'La descarga fue creada');
    }

    public function listastore(Request $request)
    {
        $lista = new Lista;
        $lista->orden = $request->orden;
        $lista->titulo = $request->titulo;
        if($request->file('imagen') === null){
            $lista->imagen = $lista->imagen;
        }else{
            $lista->imagen = $request->file('imagen')->store('public/listas');
        }
        if($request->file('archivo') === null){
            $lista->archivo = $lista->archivo;
        }else{
            $lista->archivo = $request->file('archivo')->store('public/listas');        
        }
        $lista->save();
        return redirect()->route('listas')->with('success', 'Registro creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
     { 
      //    $active = 'page.descargas';
    //     $descarga = Descarga::find($id);
    //     $descargas = Descarga::orderBy('orden', 'ASC')->get();
    //     $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
    //     $logosfooter = Logo::where('seccion', 'footer')->orderBy('id', 'ASC')->get();
    //     $contactos = Contacto::orderBy('orden', 'ASC')->get();
    //     $redes = Rede::get();
    //     return view('page.descarga', compact('descarga', 'descargas', 'logosheader', 'logosfooter', 'contactos', 'redes', 'active'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $descargas = Descarga::find($id);        

        return view('adm.descargas.editar', compact('descargas'));
    }

    public function listaedit($id)
    {
        $listas = Lista::find($id);        

        return view('adm.listas.editar', compact('listas'));
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
        $descargas = Descarga::find($id);    

        if($request->file('imagen')){
            $descargas->imagen = $request->file('imagen')->store('public/descargas');
        }
        if($request->file('archivo')){
            $descargas->archivo = $request->file('archivo')->store('public/descargas');
        }   
        $descargas->orden  = $request->orden;
        $descargas->titulo = $request->titulo;
        $descargas->save();
        return redirect()->route('deescarga')->with('success', "Registro actualizado exitósamente" );    
    }

    public function listaupdate(Request $request, $id)
    {        
        $listas = Lista::find($id);    

        if($request->file('imagen')){
            $listas->imagen = $request->file('imagen')->store('public/listas');
        }
        if($request->file('archivo')){
            $listas->archivo = $request->file('archivo')->store('public/listas');
        }   
        $listas->orden  = $request->orden;
        $listas->titulo = $request->titulo;
        $listas->save();
        return redirect()->route('listas')->with('success', "Registro actualizado exitósamente" );    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $descarga = Descarga::find($id);
        storage::delete($descarga->imagen);
        storage::delete($descarga->archivo);
        $descarga->delete();        
        
        return redirect()->back()->with('success', "Registro eliminado exitósamente" );
    }

    public function listadestroy($id)
    {
        $lista = Lista::find($id);
        storage::delete($lista->imagen);
        storage::delete($lista->archivo);
        $lista->delete();        
        
        return redirect()->back()->with('success', "Registro eliminado exitósamente" );
    }
    
}
