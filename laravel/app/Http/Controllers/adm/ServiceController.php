<?php

namespace App\Http\Controllers\adm;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\SoporteMultiSheeImport;
use App\Imports\ComprarMultiSheeImport;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($seccion)
    {
        $service = Service::where('seccion',$seccion)->orderBy('orden', 'ASC')->get();
        return view('adm.Service.listados', compact('service','seccion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($seccion)
    {           
        return view('adm.Service.crear', compact('seccion'));
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$seccion)
    {
        $Service = new Service;
        $Service->orden = $request->orden;
        $Service->nombre = $request->nombre;                
        $Service->provincia = $request->provincia;
        $Service->localidad = $request->localidad;
        $Service->correo = $request->correo;
        $Service->telefono = $request->telefono;
        $Service->latitud = $request->latitud;
        $Service->longitud = $request->longitud;
        $Service->horario = $request->horario;
        $Service->direccion = $request->direccion;
        $Service->seccion = $seccion;
  
        $Service->save();

        return redirect()->route('service',$seccion)->with('success', 'Registro creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
     { 
      //    $active = 'page.Service';
    //     $Service = Service::find($id);
    //     $Service = Service::orderBy('orden', 'ASC')->get();
    //     $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
    //     $logosfooter = Logo::where('seccion', 'footer')->orderBy('id', 'ASC')->get();
    //     $contactos = Contacto::orderBy('orden', 'ASC')->get();
    //     $redes = Rede::get();
    //     return view('page.Service', compact('Service', 'Service', 'logosheader', 'logosfooter', 'contactos', 'redes', 'active'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($seccion,$id)
    {
        $service = Service::find($id);        
        return view('adm.Service.editar', compact('service','seccion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$seccion, $id)
    {        
        $Service = Service::find($id);        

        $Service->orden = $request->orden;
        $Service->nombre = $request->nombre;                
        $Service->provincia = $request->provincia;
        $Service->localidad = $request->localidad;
        $Service->correo = $request->correo;
        $Service->telefono = $request->telefono;
        $Service->latitud = $request->latitud;
        $Service->longitud = $request->longitud;
        $Service->horario = $request->horario;
        $Service->direccion = $request->direccion;
        $Service->seccion = $seccion;

        $Service->save();
        
        return redirect()->route('service',$seccion)->with('success', "Registro actualizado exitósamente" );
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Service = Service::find($id);          
        $Service->delete();
        return redirect()->back()->with('success', "Registro eliminado exitósamente" );
    }

    public function service_import_excel(Request $request){
        $file = $request->file('file');
        
        Excel::import(new SoporteMultiSheeImport, $file);
        //API KEY =>  AIzaSyDsV-H0_IspoP-7T89nwhra1sZkqw-b0jc

        //https://maps.googleapis.com/maps/api/geocode/json?key=APIKEY&address=DIRECCION
        return back()->with('message','Importacion de service completada');
    }

    public function comprar_import_excel(Request $request){
        $file = $request->file('file');
        
        Excel::import(new ComprarMultiSheeImport, $file);
        //API KEY =>  AIzaSyDsV-H0_IspoP-7T89nwhra1sZkqw-b0jc

        //https://maps.googleapis.com/maps/api/geocode/json?key=APIKEY&address=DIRECCION
        return back()->with('message','Importacion de comprar completada');
    }
}
