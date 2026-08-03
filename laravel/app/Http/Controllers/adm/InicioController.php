<?php

namespace App\Http\Controllers\adm;

use App\Http\Controllers\Controller;
use App\Models\Inicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class InicioController extends Controller
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
        $contenido = Inicio::find($id);
        if(!is_null($contenido))
            $contenido = Inicio::find($id);
        else{
            $contenido          = new inicio();            
            
        }
        $contenido->save();

        return view('adm.inicio.editarcontenido', compact('contenido', 'id'));
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
        $contenido = Inicio::find($id);
        if(!is_null($id))
            $contenido = Inicio::find($id);
        else{
            $contenido          = new inicio();
            
        }

        if ($request->hasFile('imagen'))
        {   $path               = $request->file('imagen')->store('public/inicio');
            $contenido->imagen  = $path;
        }
        else
            $contenido->imagen  = $contenido->imagen;

        $contenido->texto = $request->texto;
        $contenido->save();
        return redirect()->route('editarinicio', ['id'=>$id])->with('success', "Registro actualizado exitósamente" );
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
    
    public function borrarproducto($id, $img)
    {
        $productos = inicio::find($id);

        $productos->save();

        return redirect()->back();

        
      
          
    }
}
