<?php

namespace App\Http\Controllers\adm;
use App\Models\Producto;
use App\Models\Color;
use App\Models\ProductoRelacion;
use App\Models\ColoresRelacion;
use App\Models\PresentacionRelacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\FamiliaProducto;
use App\Models\Categoria;
use App\Imports\ProductoMultiSheeImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Producto::with(['obtenerFamilia', 'obtenerSubCategoria', 'obtenerPresentacionRelacionados']);
        $busqueda = trim((string) $request->query('q', ''));

        if ($busqueda !== '') {
            $query->where(function ($subquery) use ($busqueda) {
                if (ctype_digit($busqueda)) {
                    $subquery->orWhere('id', (int) $busqueda);
                }

                $subquery->orWhere('nombre', 'LIKE', "%{$busqueda}%")
                    ->orWhere('descripcion', 'LIKE', "%{$busqueda}%")
                    ->orWhere('color', 'LIKE', "%{$busqueda}%")
                    ->orWhereHas('obtenerFamilia', function ($familia) use ($busqueda) {
                        $familia->where('nombre', 'LIKE', "%{$busqueda}%");
                    })
                    ->orWhereHas('obtenerSubCategoria', function ($subfamilia) use ($busqueda) {
                        $subfamilia->where('nombre', 'LIKE', "%{$busqueda}%");
                    })
                    ->orWhereHas('obtenerPresentacionRelacionados', function ($presentacion) use ($busqueda) {
                        $presentacion->where('codigo', 'LIKE', "%{$busqueda}%")
                            ->orWhere('presentacion', 'LIKE', "%{$busqueda}%")
                            ->orWhere('precio', 'LIKE', "%{$busqueda}%");
                    });
            });
        }

        $activa = $request->query('activa');
        if ($activa !== null && $activa !== '' && in_array($activa, ['0', '1'], true)) {
            $query->where('activa', $activa);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categorias_id', $request->query('categoria_id'));
        }

        if ($request->filled('subcategoria_id')) {
            $query->where('subcategorias_id', $request->query('subcategoria_id'));
        }

        $productos = $query->orderBy('categorias_id','DESC')
            ->orderBy('subcategorias_id','DESC')
            ->orderBy('orden', 'ASC')
            ->paginate(25)
            ->appends($request->query());

        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $subcategorias = FamiliaProducto::orderBy('categorias_id','DESC')->get();

        return view('adm.productos.contenido', compact('productos','categorias','subcategorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $productos  = Producto::orderBy('categorias_id','DESC')->orderBy('orden', 'ASC')->get();
        $subcategorias   = Familiaproducto ::orderBy('categorias_id','DESC')->get();
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $colors = Color::orderBy('orden', 'ASC')->get();
        return view('adm.productos.crear', compact('productos','categorias','subcategorias','colors'));
    }
    
     public function precios(){
        $categorias = Categoria::all();
        return view('adm.productos.precios', compact('categorias'));
    }
    public function updateprecio(Request $request){
        $aumento = $request->aumento;
        $aumento = intval($aumento);
        $aumento = $aumento / 100;
        $aumento = $aumento + 1;
        if(isset($request->categorias)){
            foreach($request->categorias as $id){
                $categoria = Categoria::find($id);
                foreach($categoria->obtenerProductos as $familias){                    
                    foreach($familias->obtenerProductos as $productos){
                        foreach($productos->obtenerPresentacionRelacionados as $presentacion){
                            $precio = $presentacion->precio * $aumento;
                            $presentacion->precio = $precio;
                            $presentacion->save();                     
                        }   
                    }                    
                }   
            }
        }
        
        return redirect()->route('precios')->with('success', "Precios Actualizados");    
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $productos = new Producto;        
        $productos->nombre = $request->nombre;
        $productos->color = $request->color;
        $productos->descripcion = $request->descripcion;
        if($request->categorias_id != 0){            
            $productos->categorias_id = $request->categorias_id;
        }
        if($request->subcategorias_id != 0){            
            $productos->subcategorias_id = $request->subcategorias_id;
        }
        
        $productos->orden = $request->orden;
        if ($request->hasFile('imagen')) {
            $productos->imagen = $request->file('imagen')->store('public/productos');
        }
        $productos->destacado = 0;
        if ($request->destacado) {
            $productos->destacado = 1;
        }

        $productos->activa = 0;
        if ($request->activa) {
            $productos->activa = 1;
        }

        if ($request->hasFile('galeria')) {
            $fotos = $request->file('galeria');
            $arrayimg = array(); 
            foreach($fotos as $foto){
                $galeria = $foto->store('public/productos');
                array_push($arrayimg, $galeria);
            }
            
            $productos->galeria = implode(',', $arrayimg);
        }   
        
        $productos->save();

        if(isset($request->relacionado)){
            foreach($request->relacionado as $relacion){
                $objrelacion = new ProductoRelacion;
                $objrelacion->producto_id = $productos->id;
                $objrelacion->relacion_id = $relacion;
                $objrelacion->save();
            }
        }

        if(isset($request->colores)){
            foreach($request->colores as $relacion){
                $objrelacion = new ColoresRelacion;
                $objrelacion->producto_id = $productos->id;
                $objrelacion->relacion_id = $relacion;
                $objrelacion->save();
            }
        }

        if(isset($request->presentacion)){
            for($i=0; $i<count($request->presentacion); $i++){
                $objrelacion = new PresentacionRelacion;
                $objrelacion->producto_id = $productos->id;
                $objrelacion->presentacion = $request->presentacion[$i];
                $objrelacion->precio = $request->precio[$i];
                $objrelacion->stock = $request->stock[$i];
                $objrelacion->codigo = $request->codigoP[$i];
                $objrelacion->save();
            }            
        }

        return redirect()->route('Productos')->with('success', 'Registro creado');
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
        //     $producto = Producto::find($id);
        //     $productos = Producto::orderBy('orden', 'ASC')->get();
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
        $productos = Producto::find($id);
        $subcategorias   = Familiaproducto ::orderBy('categorias_id','DESC')->get();
        $categorias = Categoria::orderBy('orden', 'ASC')->get();
        $colores = Color::orderBy('orden', 'ASC')->get();
        $productosall = Producto::orderBy('orden','ASC')->get();
        return view('adm.productos.editar', compact('productos','categorias','subcategorias','colores','productosall'));
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
        $productos = Producto::find($id);        
        $productos->nombre = $request->nombre;
        $productos->color = $request->color;
        $productos->descripcion = $request->descripcion;
        if($request->categorias_id != 0){
            $productos->categorias_id = $request->categorias_id;
        }
        if($request->subcategorias_id != 0){            
            $productos->subcategorias_id = $request->subcategorias_id;
        }
        
        $productos->orden = $request->orden;
        if ($request->hasFile('imagen')) {
            $productos->imagen = $request->file('imagen')->store('public/productos');
        }

        $productos->destacado = 0;
        if ($request->destacado) {
            $productos->destacado = 1;
        }
        $productos->activa = 0;
        if ($request->activa) {

            $productos->activa = 1;
        }

        if ($request->hasFile('galeria')) {
            $fotos = $request->file('galeria');
            $arrayimg = array(); 
            foreach($fotos as $foto){
                $galeria = $foto->store('public/productos');
                array_push($arrayimg, $galeria);
            }
            
            $productos->galeria = implode(',', $arrayimg);
        }   
        
        $productos->save();
        ProductoRelacion::where('producto_id','=',$productos->id)->delete(); 
        if(isset($request->relacionado)){
            foreach($request->relacionado as $relacion){
                $objrelacion = new ProductoRelacion;
                $objrelacion->producto_id = $productos->id;
                $objrelacion->relacion_id = $relacion;
                $objrelacion->save();
            }
        }
        
        ColoresRelacion::where('producto_id','=',$productos->id)->delete(); 
        if(isset($request->colores)){
            foreach($request->colores as $relacion){
                $objrelacion = new ColoresRelacion;
                $objrelacion->producto_id = $productos->id;
                $objrelacion->relacion_id = $relacion;
                $objrelacion->save();
            }
        }
        
        
        if(isset($request->presentacion)){
			//dd($request->presentacion);
            for($i=0; $i<count($request->presentacion); $i++){
				if($request->presentacion[$i]){

	                //$objrelacion = PresentacionRelacion::where('producto_id','=',$productos->id)->where('presentacion','=',$request->presentacion[$i])->where('codigo','=',$request->codigoP[$i])->first();
					$objrelacion = PresentacionRelacion::find($request->idrelacion[$i]);
	
					if($request->precio[$i] == "0" && $request->stock[$i] == "0"){
						if($objrelacion){
							$objrelacion->delete();
						}

					}else{
						if(!$objrelacion){
							$objrelacion = new PresentacionRelacion;	
						}
						$objrelacion->producto_id = $productos->id;
						$objrelacion->presentacion = $request->presentacion[$i];
						$objrelacion->precio = $request->precio[$i];
						$objrelacion->stock = $request->stock[$i];
						$objrelacion->codigo = $request->codigoP[$i];
						$objrelacion->save();

					}
				}
                
            }            
        }


        return redirect()->route('Productos')->with('success', "Registro actualizado exitósamente" );    
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->query('confirm') !== '1') {
            return redirect()->route('Productos')->with('warning', 'No se elimino el producto. Para borrar primero debe confirmar la accion.');
        }

        $producto = Producto::find($id);

        if (!$producto) {
            return redirect()->route('Productos')->with('warning', 'El producto no existe o ya fue eliminado.');
        }

        Storage::delete($producto->imagen);
        $producto->delete();
        ProductoRelacion::where('producto_id','=',$producto->id)->delete(); 
        PresentacionRelacion::where('producto_id','=',$producto->id)->delete(); 
        ColoresRelacion::where('producto_id','=',$producto->id)->delete(); 
        
        return redirect()->back()->with('success', "Registro eliminado exitósamente" );
    }


    public function borrarproducto($id, $img)
    {
        $productos = Producto::find($id);
        $explode = explode(',', $productos->galeria);
        unset($explode[$img]);
        $productos->galeria = implode(',', $explode);
        $productos->save();
        return redirect()->back();
    }

    public function productos_import_excel(Request $request){
        $file = $request->file('file');
        Excel::import(new ProductoMultiSheeImport, $file);
        //API KEY =>  AIzaSyDsV-H0_IspoP-7T89nwhra1sZkqw-b0jc
        //https://maps.googleapis.com/maps/api/geocode/json?key=APIKEY&address=DIRECCION
        return back()->with('message','Importacion de productos completada');
    }
}
