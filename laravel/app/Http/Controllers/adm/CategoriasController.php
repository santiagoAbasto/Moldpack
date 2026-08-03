<?php

namespace App\Http\Controllers\adm;
use App\Models\Categoria;
use App\Models\Contenido;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Categorias = Categoria::orderBy('orden', 'ASC')->get();
        $contenido = Contenido::where('seccion','=','categorias')->first();
        return view('adm.Categorias.contenido', compact('Categorias','contenido'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $Categorias  = Categoria::orderBy('orden', 'ASC')->get();        
        return view('adm.Categorias.crear', compact('Categorias'));
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       
        //dd($request->all());
        $Categoria = new Categoria;
        $Categoria->orden = $request->orden;
        $Categoria->nombre = $request->nombre;                
        $Categoria->descripcion = $request->descripcion;        
        $Categoria->imagen = $request->file('imagen')->store('public/Categorias');        
        $Categoria->destacado = 0;
        if ($request->destacado == "on") {
            $Categoria->destacado = 1;
        }
        $Categoria->activa = 0;
        if ($request->activa == "on") {
            $Categoria->activa = 1;
        }
        $Categoria->save();
        // if(isset($request->aplicacion)){
        //     foreach($request->aplicacion as $relacion){
        //         $objrelacion = new AplicacionRelacion;
        //         $objrelacion->producto_id = $Categoria->id;
        //         $objrelacion->relacion_id = $relacion;
        //         $objrelacion->save();
        //     }
        // }
        return redirect()->route('Categorias')->with('success', 'La familia fue creada');
    }

    public function imgcategorias(Request $request)
    {
        $contenido = Contenido::where('seccion','=','categorias')->first();
 
        if(!$contenido){        
            $contenido = new Contenido();            
        }
        if($request->file('imagen') === null){
            
        }else{
            $contenido->imagen = $request->file('imagen')->store('public/categorias');
        }
        $contenido->seccion = "categorias";
        $contenido->save();
              
        
        return redirect()->route('Categorias')->with('success', 'Registro guardado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
     { 
      //    $active = 'page.Categorias';
    //     $Categoria = Categoria::find($id);
    //     $Categorias = Categoria::orderBy('orden', 'ASC')->get();
    //     $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
    //     $logosfooter = Logo::where('seccion', 'footer')->orderBy('id', 'ASC')->get();
    //     $contactos = Contacto::orderBy('orden', 'ASC')->get();
    //     $redes = Rede::get();
    //     return view('page.Categoria', compact('Categoria', 'Categorias', 'logosheader', 'logosfooter', 'contactos', 'redes', 'active'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Categorias = Categoria::find($id);
        // $aplicacion = Aplicacion::orderBy('orden', 'ASC')->get();
        
        return view('adm.Categorias.editar', compact('Categorias'));
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
        $Categorias = Categoria::find($id);       

        if($request->file('imagen')){
            $Categorias->imagen = $request->file('imagen')->store('public/Categorias');
        }
        if($request->destacar){
            $Categorias->destacar= $request->destacar;
        }
        $Categorias->orden     = $request->orden;
        $Categorias->nombre = $request->nombre;        
        $Categorias->descripcion = $request->descripcion;
        $Categorias->destacado = 0;
        if ($request->destacado == "on") {
            $Categorias->destacado = 1;
        }
        $Categorias->activa = 0;
        if ($request->activa == "on") {
            $Categorias->activa = 1;
        }
        // AplicacionRelacion::where('producto_id','=',$Categorias->id)->delete(); 
        // if(isset($request->aplicacion)){
        //     foreach($request->aplicacion as $relacion){
        //         $objrelacion = new AplicacionRelacion;
        //         $objrelacion->producto_id = $Categorias->id;
        //         $objrelacion->relacion_id = $relacion;
        //         $objrelacion->save();
        //     }
        // }
        

        $Categorias->save();

        return redirect()->route('Categorias')->with('success', "Registro actualizado exitósamente" );
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $Categoria = Categoria::find($id);
        storage::delete($Categoria->imagen);
        $Categoria->delete();
        return redirect()->back()->with('success', "Registro eliminado exitósamente" );
    }

}
