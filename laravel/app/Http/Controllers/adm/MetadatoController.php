<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Metadato;

class MetadatoController extends Controller
{
    public function index()
    {
        $metadata = Metadato::orderBy('id', 'ASC')->get();
        return view('adm.metadato.index', compact('metadata'));
    }

    public function edit($id)
    {
        $metadata = Metadato::find($id);
        return view('adm.metadato.edit', compact('metadata'));
    }

    public function update(Request $request, $data)
    {
        $metadata = Metadato::find($data);
        $metadata->keyword      = $request->keyword;
        $metadata->descripcion  = $request->descripcion;
        $metadata->seccion      = $request->seccion;
        $metadata->save();
        return redirect()->route('metadatos')->with('success', "Registro actualizado exitósamente" );
    
    }
}
