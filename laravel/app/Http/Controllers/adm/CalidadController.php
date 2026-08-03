<?php

namespace App\Http\Controllers\adm;

use App\Http\Controllers\Controller;
use App\Models\Calidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalidadController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contenido  $contenido
     * @return \Illuminate\Http\Response
     */
    public function show(Contenido $contenido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contenido  $contenido
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contenido = Calidad::find($id);

            if(!is_null($contenido))
                $contenido = Calidad::find($id);
            else{
                $contenido = new Calidad();            
                
            }

            $contenido->save();
        return view('adm.calidad.editarcontenido', compact('contenido', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contenido  $contenido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contenido = Calidad::find($id);
        if(!is_null($id))
            $contenido = Calidad::find($id);
        else{
            $contenido          = new Calidad();
            
        }   
        
        if($request->file('imagen')){
            $contenido->imagen = $request->file('imagen')->store('public/calidad');
        }
        if($request->file('certificado')){
            $contenido->certificado = $request->file('certificado')->store('public/calidad');
        }
        if($request->file('politicas')){
            $contenido->politicas = $request->file('politicas')->store('public/calidad');
        }
        
        $contenido->texto = $request->texto;
        $contenido->save();
        return redirect()->route('editarcalidad', ['id'=>$id])->with('success', "Registro actualizado exitósamente" );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contenido  $contenido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contenido $contenido)
    {
        //
    }


}
