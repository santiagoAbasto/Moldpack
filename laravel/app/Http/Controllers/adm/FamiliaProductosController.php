<?php

namespace App\Http\Controllers\adm;
use App\Models\FamiliaProducto;
use App\Models\AplicacionRelacion;
use App\Models\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductoRelacion;
use App\Imports\ProductoMultiSheeImport;
use Maatwebsite\Excel\Facades\Excel;

class FamiliaProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Familiaproductos = FamiliaProducto::orderBy('orden', 'ASC')->get();
        return view('adm.FamiliaProductos.contenido', compact('Familiaproductos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $Familiaproductos  = FamiliaProducto::orderBy('orden', 'ASC')->get();
        $Categoria  = Categoria::orderBy('orden', 'ASC')->get();
        $productos  = FamiliaProducto::orderBy('orden', 'ASC')->get();
        return view('adm.FamiliaProductos.crear', compact('Familiaproductos','Categoria','productos'));
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Familiaproducto = new Familiaproducto;
        $Familiaproducto->nombre = $request->nombre;        
        $Familiaproducto->categorias_id = $request->producto;        
        if ($request->hasFile('imagen')) {
            $Familiaproducto->imagen = $request->file('imagen')->store('public/productos');
        }
        $Familiaproducto->activa = 0;
        if ($request->activa == "on") {
            $Familiaproducto->activa = 1;
        }
        $Familiaproducto->save();        

        return redirect()->route('familiaProductos')->with('success', 'La familia fue creada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
     { 
      //    $active = 'page.Familiaproductos';
    //     $Familiaproducto = FamiliaProducto::find($id);
    //     $Familiaproductos = FamiliaProducto::orderBy('orden', 'ASC')->get();
    //     $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
    //     $logosfooter = Logo::where('seccion', 'footer')->orderBy('id', 'ASC')->get();
    //     $contactos = Contacto::orderBy('orden', 'ASC')->get();
    //     $redes = Rede::get();
    //     return view('page.Familiaproducto', compact('Familiaproducto', 'Familiaproductos', 'logosheader', 'logosfooter', 'contactos', 'redes', 'active'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Familiaproductos = FamiliaProducto::find($id);
        $Categoria  = Categoria::orderBy('orden', 'ASC')->get();
        $productos  = FamiliaProducto::find($id);
        $productosall = FamiliaProducto::all();
        return view('adm.FamiliaProductos.editar', compact('Familiaproductos','Categoria','productos','productosall'));
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
        $Familiaproductos = FamiliaProducto::find($id);        

        $Familiaproductos->nombre = $request->nombre;
        $Familiaproductos->categorias_id = $request->producto;        
        $Familiaproductos->orden = $request->orden;
        if ($request->hasFile('imagen')) {
            $Familiaproductos->imagen = $request->file('imagen')->store('public/productos');
        }
        $Familiaproductos->activa = 0;
        if ($request->activa == "on") {
            $Familiaproductos->activa = 1;
        }

        $Familiaproductos->save();
        
        return redirect()->route('familiaProductos')->with('success', "Registro actualizado exitósamente" );
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $Familiaproducto = FamiliaProducto::find($id);
        storage::delete($Familiaproducto->imagen);        
        ProductoRelacion::where('producto_id','=',$Familiaproducto->id)->delete(); 
        $Familiaproducto->delete();
        return redirect()->back()->with('success', "Registro eliminado exitósamente" );
    }    
}
