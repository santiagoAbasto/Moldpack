<?php

namespace App\Http\Controllers\adm;

use App\Models\Logo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogosController extends Controller
{
    public function index()
    {
        $logos = Logo::orderBy('id', 'ASC')->get();
        return view('adm.logos.contenido', compact('logos'));
    }
    public function create()
    {
        return view('adm.logo.create');
    }

    public function store(Request $request)
    {
       

    }

    public function edit($id)
    {
        $logos = Logo::find($id);
        return view('adm.logos.editar', compact('logos'));
    }

    public function update(Request $request, $id)
    {
        
        $logos = Logo::find($id);
        if ($request->hasFile('imagen'))
        {
            Storage::delete($logos->imagen);

            $logos->imagen = $request->file('imagen')->store('public/logos');
        }
        $logos->save();
        return redirect()->route('logos')->with('success', "Registro actualizado exitósamente" );
      } 
}
