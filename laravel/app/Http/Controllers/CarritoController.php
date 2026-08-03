<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarritoContenido;

class CarritoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function carrito(){
        $carrito = CarritoContenido::first();

        return view('adm.carrito.editar',compact('carrito'));
    }
    public function carrito_post(Request $request){
        
        $carrito = new CarritoContenido();

        if(isset($request->titulo)){
            $carrito->titulo = $request->titulo;
        }else{
            $carrito->titulo = "";
        }
        if(isset($request->texto)){
            $carrito->texto = $request->texto;
        }else{
            $carrito->texto = "";
        }
        
        if(isset($request->iva)){
            $carrito->iva = $request->iva;
        }else{
            $carrito->iva = 0;
        }

        if(isset($request->limite)){
            $carrito->limite = $request->limite;
        }else{
            $carrito->limite = 0;
        }

        if(isset($request->costo)){
            $carrito->costo = $request->costo;
        }else{
            $carrito->costo = 0;
        }
        

        if(isset($request->descuento)){
            $carrito->descuento = $request->descuento;
        }else{
            $carrito->descuento = "";
        }
        $carrito->save();
        return redirect()->route('carrito_zp');   
    }
    public function carrito_put(Request $request,$id){
        
        
        $carrito = CarritoContenido::findorFail($id);

        
        if(isset($request->iva)){
            $carrito->iva = $request->iva;
        }else{
            $carrito->iva = 0;
        }

        if(isset($request->titulo)){
            $carrito->titulo = $request->titulo;
        }else{
            $carrito->titulo = "";
        }
        if(isset($request->texto)){
            $carrito->texto = $request->texto;
        }else{
            $carrito->texto = "";
        }

        if(isset($request->descuento)){
            $carrito->descuento = $request->descuento;
        }else{
            $carrito->descuento = 0;
        }

        if(isset($request->limite)){
            $carrito->limite = $request->limite;
        }else{
            $carrito->limite = 0;
        }

        $carrito->save();
        return redirect()->route('carrito_zp');   
    }
    public function carrito_delete(Request $request,$id){
        $carrito = CarritoContenido::findorFail($id);
        $carrito->delete();
        return redirect()->route('carrito_zp');   
    }
}
