<?php

namespace App\Http\Controllers\adm;
use App\Models\Color;
use App\Models\ColorRelacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\ColorMultiSheeImport;
use Maatwebsite\Excel\Facades\Excel;

class ColorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors  = Color::orderBy('orden', 'ASC')->get();                   
        return view('adm.colors.contenido', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $colors  = Color::orderBy('orden', 'ASC')->get();        
        
        return view('adm.colors.crear', compact('colors'));
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $color = new Color;        
        $color->nombre = $request->nombre;
        $color->color = $request->color;
        
        $color->save();       

        return redirect()->route('Colors')->with('success', 'Registro creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
     { 
      //    $active = 'page.productos';
        //     $producto = Color::find($id);
        //     $productos = Color::orderBy('orden', 'ASC')->get();
        //     $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        //     $logosfooter = Logo::where('seccion', 'footer')->orderBy('id', 'ASC')->get();
        //     $contactos = Contacto::orderBy('orden', 'ASC')->get();
        //     $redes = Rede::get();
        //     return view('page.producto', compact('producto', 'productos', 'logosheader', 'logosfooter', 'contactos', 'redes', 'active'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $colors = Color::find($id);
        return view('adm.colors.editar', compact('colors'));
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
        $color = Color::find($id);        
        $color->nombre = $request->nombre;
        $color->color = $request->color;        
        $color->save();


        return redirect()->route('Colors')->with('success', "Registro actualizado exitósamente" );    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $color = Color::find($id);        
        $color->delete();
        ColorRelacion::where('producto_id','=',$color->id)->delete(); 
        
        return redirect()->back()->with('success', "Registro eliminado exitósamente" );
    }
}
