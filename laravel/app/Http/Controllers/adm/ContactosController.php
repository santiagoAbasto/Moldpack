<?php

namespace App\Http\Controllers\adm;

use App\Http\Controllers\Controller;
use App\Models\Contacto;

use Illuminate\Http\Request;

class ContactosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacto = Contacto::get();
        
        return view('adm.contacto.contenido', compact('contacto'));
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
         $contacto = Contacto::find($id);

         if(!is_null($contacto))
         $contacto = Contacto::find($id);
        else{
            $contacto = new Contacto();            
            
        }
        $contacto->save();
        
        return view('adm.contacto.editar', compact('contacto'));
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
        $contacto = Contacto::find($id);

        if($request->file('imagen')){
            $contacto->imagen = $request->file('imagen')->store('public/contacto');
        }
        
        $contacto->direccion = $request->direccion;
        $contacto->celular = $request->celular;
        $contacto->telefono = $request->telefono;
        $contacto->correo = $request->correo;
        $contacto->correoF = $request->correoF;
        $contacto->telefono2 = $request->telefono2;
        
        $contacto->save();
        return redirect()->route('contacto')->with('success', "Registro actualizado exitósamente" );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
