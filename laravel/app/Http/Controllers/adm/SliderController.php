<?php

namespace App\Http\Controllers\adm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index($seccion)
    {
        $slider = Slider::where('seccion',$seccion)->orderBy('orden', 'asc')->get();

        return view('adm.slider.listados', compact('slider', 'seccion'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($seccion)
    {
        return view('adm.slider.crear', compact('seccion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $seccion)
    {

        $slider = new Slider;
        $slider->orden = $request->orden;
        $slider->activo = 0;
        if ($request->activo == "on") {
            $slider->activo = 1;
        }
        $slider->descripcion = $request->descripcion;
        $slider->boton = $request->boton;
        $slider->link = $request->link;
        $slider->seccion = $seccion;
        $slider->imagen = $request->file('imagen')->store('public/sliders');
        $slider->save();


        return redirect()->route('slider',$seccion)->with('success', 'El slider fue creado');
     
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
    public function edit($seccion, $id)
    {
        $slider = Slider::find($id);

        return view('adm.slider.editar', compact('slider', 'seccion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$seccion, $id )
    {
        $slider = Slider::find($id);
        if ($request->hasFile('imagen'))
        {
            Storage::delete($slider->imagen);
            $path = $request->file('imagen')->store('public/sliders');
        }else{
            $path = $slider->imagen;
        }        
        $slider->descripcion = $request->descripcion;
        
        $slider->activo = 0;
        if ($request->activo == "on") {
            $slider->activo = 1;
        }
        
        $slider->imagen    = $path;
        $slider->seccion   = $seccion;
        $slider->boton = $request->boton;
        $slider->link = $request->link;
        $slider->orden     = $request->orden;        
        $slider->save();
        return redirect()->route('slider', $seccion)->with('success', "Registro actualizado exitósamente" );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$slider = Slider::where('seccion', $seccion)->where('id', $id)->first();
        $slider = Slider::find($id);
        //storage::delete($slider->imagen);
        $slider->delete();
        return redirect()->back()->with('danger', "Registro eliminado exitósamente" );
    }
}
