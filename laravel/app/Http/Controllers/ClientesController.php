<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DescuentoRelacion;
use App\Models\CategoriasRelacion;
use App\Models\FamiliaProducto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Exports\MultiSheetsExport;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;
class ClientesController extends Controller
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
    private function filtrarClientes(Request $request)
    {
        $query = Cliente::query();
        $busqueda = trim((string) $request->query('q', ''));

        if ($busqueda !== '') {
            $query->where(function ($subquery) use ($busqueda) {
                if (ctype_digit($busqueda)) {
                    $subquery->orWhere('id', (int) $busqueda)
                        ->orWhere('cuit', 'LIKE', "%{$busqueda}%")
                        ->orWhere('dni', 'LIKE', "%{$busqueda}%");
                }

                $subquery->orWhere('username', 'LIKE', "%{$busqueda}%")
                    ->orWhere('razonSocial', 'LIKE', "%{$busqueda}%")
                    ->orWhere('nombre', 'LIKE', "%{$busqueda}%")
                    ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                    ->orWhere('email', 'LIKE', "%{$busqueda}%")
                    ->orWhere('emailAux', 'LIKE', "%{$busqueda}%")
                    ->orWhere('telefono', 'LIKE', "%{$busqueda}%")
                    ->orWhere('direccion', 'LIKE', "%{$busqueda}%")
                    ->orWhere('direccionEntrega', 'LIKE', "%{$busqueda}%");
            });
        }

        $estado = $request->query('estado');
        if ($estado !== null && $estado !== '' && in_array($estado, ['0', '1'], true)) {
            $query->where('estado', $estado);
        }

        return $query;
    }

    public function cliente(Request $request){

        $Clientes = $this->filtrarClientes($request)
            ->orderByRaw('created_at IS NULL')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->appends($request->query());
        $productos = Categoria::orderBy('orden','asc')->get();
        
        return view('adm.clientes.contenido', compact('Clientes','productos'));
    }
    
    public function clienteBusqueda(Request $request){
        $parametros = [
            'q' => $request->input('q', $request->input('email')),
            'estado' => $request->input('estado'),
        ];

        $parametros = array_filter($parametros, function ($valor) {
            return $valor !== null && $valor !== '';
        });

        return redirect()->route('clientes.view', $parametros);
    }

    public function create(){
        $productos = Categoria::orderBy('id','asc')->orderBy('orden','asc')->get();
        return view('adm.clientes.crear',compact('productos'));
    }

    public function update($id){
        $cliente = Cliente::findorFail($id);
        $productos = Categoria::orderBy('id','asc')->orderBy('orden','asc')->get();        
        return view('adm.clientes.editar',compact('cliente','productos'));
    }

    private function passwordViewKeyIsValid(Request $request): bool
    {
        $provided = (string) $request->input('password_view_key', '');

        if ($provided === '') {
            return false;
        }

        $configuredHash = (string) env('CLIENT_PASSWORD_VIEW_KEY_HASH', '');
        if ($configuredHash !== '') {
            return Hash::check($provided, $configuredHash);
        }

        $configuredKey = (string) env('CLIENT_PASSWORD_VIEW_KEY', '');

        return $configuredKey !== '' && hash_equals($configuredKey, $provided);
    }

    private function passwordViewKeyErrorMessage(): string
    {
        if (!env('CLIENT_PASSWORD_VIEW_KEY_HASH') && !env('CLIENT_PASSWORD_VIEW_KEY')) {
            return 'La clave unica de vista de contraseñas no esta configurada en el servidor.';
        }

        return 'La clave unica de vista de contraseñas es incorrecta.';
    }

    public function verificarClavePassword(Request $request)
    {
        if (!$this->passwordViewKeyIsValid($request)) {
            return response()->json([
                'ok' => false,
                'message' => $this->passwordViewKeyErrorMessage(),
            ], 403);
        }

        return response()->json(['ok' => true]);
    }

    public function clientes_post(request $request){
        
         $user = new Cliente;
         
        // $vendedor = new vendedor_cliente;

        
         $request->validate([
            'username' => 'required|unique:clientes,username',
            'email' => 'required|email|unique:clientes,email',
            'password' => 'required|string|min:8|confirmed',
          ], [
            'username.required' => 'Ingrese un usuario.',
            'username.unique' => 'Este usuario ya existe.',
            'email.required' => 'Ingrese un email.',
            'email.email' => 'Ingrese un email valido.',
            'email.unique' => 'Este email ya esta registrado.',
            'password.required' => 'Ingrese una contrasena.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
          ]);

          $passwordPlano = (string) $request->password;

          $user->username = $request->username;
          $user->nombre = $request->nombre;
          $user->direccionEntrega = $request->direccionEntrega;
          $user->telefono = $request->telefono;
          $user->razonSocial = $request->razonSocial;
		  $user->fechaInicio = date("d/m/o");
          $user->apellido = $request->apellido;
          $user->direccion = $request->direccion;
          $user->dni = $request->dni;
          $user->cuit = 0;
          if($request->cuit){
            $user->cuit = $request->cuit;
          }
          $user->password = Hash::make($passwordPlano);
          try { $user->password_encrypted = Crypt::encryptString($passwordPlano); } catch (\Exception $e) {}
          $user->email = $request->email;          
          
          $user->descuento = 0;
          if(isset($request->descuentogeneral)){
            $user->descuento = $request->descuentogeneral;
          }
          

          $user->estado = 0;
          if(isset($request->estado)){
            $user->estado = $request->estado;
          }

          $user->precios = 0;
          if(isset($request->precios)){
            $user->precios = $request->precios;
          }
          
          $user->save();

          if(isset($request->descuento)){
            for($i=0;$i<count($request->descuento);$i++){
              $descuentoRelacion = new DescuentoRelacion();
              $descuentoRelacion->producto_id = $request->producto[$i];
              $descuentoRelacion->cliente_id = $user->id;
              $descuentoRelacion->descuento = $request->descuento[$i];
              $descuentoRelacion->save();
            }
          }

          if(isset($request->categorias)){
            for($i=0;$i<count($request->categorias);$i++){
              $CategoriasRelacion = new CategoriasRelacion();
              $CategoriasRelacion->relacion_id = $request->categorias[$i];
              $CategoriasRelacion->cliente_id = $user->id;              
              $CategoriasRelacion->save();
            }
          }

          return redirect()->route('clientes.view');
    }

    public function clientes_put(request $request, $id){       
        
          $user = Cliente::findorFail($id);
      
	          request()->validate([
	            'username' => 'required|unique:clientes,username,'.$user->id,
	            'email' => 'required|unique:clientes,email,'.$user->id,
	            'password' => 'nullable|string|min:8|confirmed',
	            'password_view_key' => 'nullable|string',
	          ]);

          if($request->filled('password') && !$this->passwordViewKeyIsValid($request)){
            return back()
              ->withErrors(['password_view_key' => $this->passwordViewKeyErrorMessage()])
              ->withInput($request->except(['password', 'password_confirmation', 'password_view_key']));
          }

          $user->username = $request->username;
                 
          if(isset($request->email)){
            $user->email = $request->email;
          }else{
            $user->email = $user->email;
          }
          
          
          $user->nombre = $request->nombre;
          $user->razonSocial = $request->razonSocial;
          $user->direccionEntrega = $request->direccionEntrega;
          $user->telefono = $request->telefono;
		  $user->fechaInicio = $request->fechaInicio;
		  $user->emailAux = $request->emailAux;
          $user->apellido = $request->apellido;
          $user->direccion = $request->direccion;
          $user->dni = $request->dni;
          $user->cuit = 0;
          if($request->cuit){
            $user->cuit = $request->cuit;
          }
          
     
          if($request->filled('password')){
            $user->password = Hash::make($request->password);
            try { $user->password_encrypted = Crypt::encryptString($request->password); } catch (\Exception $e) {}
          }

          $user->descuento = 0;
          if(isset($request->descuentogeneral)){
            $user->descuento = $request->descuentogeneral;
          }

            
          $user->estado = 0;
          if(isset($request->estado)){
            $user->estado = $request->estado;
          }

          $user->precios = 0;
          if(isset($request->precios)){
            $user->precios = $request->precios;
          }

          $user->save();          
          DescuentoRelacion::where('cliente_id','=',$user->id)->delete();
          if(isset($request->descuento)){
            for($i=0;$i<count($request->descuento);$i++){
              $descuentoRelacion = new DescuentoRelacion();
              $descuentoRelacion->producto_id = $request->producto[$i];
              $descuentoRelacion->cliente_id = $user->id;
              $descuentoRelacion->descuento = $request->descuento[$i];
              $descuentoRelacion->save();
            }
          }
          CategoriasRelacion::where('cliente_id','=',$user->id)->delete();
          if(isset($request->categorias)){
            for($i=0;$i<count($request->categorias);$i++){
              $CategoriasRelacion = new CategoriasRelacion();
              $CategoriasRelacion->relacion_id = $request->categorias[$i];
              $CategoriasRelacion->cliente_id = $user->id;              
              $CategoriasRelacion->save();
            }
          }
        
          return redirect()->route('clientes.view');
    }

    public function verPasswordCliente(Request $request, $id)
    {
        if (!$this->passwordViewKeyIsValid($request)) {
            return response()->json([
                'ok' => false,
                'message' => $this->passwordViewKeyErrorMessage(),
            ], 403);
        }

        $cliente = Cliente::findOrFail($id);

        $encrypted = (string) ($cliente->password_encrypted ?? '');
        if ($encrypted !== '') {
            try {
                $password = Crypt::decryptString($encrypted);
                return response()->json(['ok' => true, 'password' => $password]);
            } catch (\Exception $e) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se pudo desencriptar la contrasena.',
                ], 500);
            }
        }

        $current = (string) $cliente->password;
        if ($current !== '' && substr($current, 0, 2) !== '$2') {
            return response()->json(['ok' => true, 'password' => $current]);
        }

        return response()->json([
            'ok' => false,
            'message' => 'La contrasena actual esta hasheada y no se puede recuperar. Establezca una nueva.',
        ], 404);
    }

    public function clientes_delete(request $request, $id){
        
         $user = Cliente::findorFail($id);
         $user->estado = 0;
         $user->save();
        
         return redirect()->route('clientes.view');
    }

    public function email(){
      return view('adm.clientes.email');
    }

    public function enviar(Request $request){
      $users=Cliente::all();
      $asunto = $request->asunto;
      $body = $request->texto;
      $from='noresponder@siwo.com.ar';
      foreach($users as $user){
       $to = $user->email;

       Mail::send('emails.masivo',
        array(  
            'texto' => $body,
        ), function($message) use ($from, $to, $asunto)
        {
        $message->from($from);
        $message->to($to)->subject($asunto);
        });

      } 
      return back()->with('success', ' Mensaje enviado!. ');
  }
    public function exportCliente($clientes){
        $arrRow = [];
        for($i = 0; $i < count($clientes); $i++){
            $row = new stdClass();
            $row->activo =$clientes[$i]->activo == 1 ? "Si" : "No";
			$row->estado = $clientes[$i]->estado == 1 ? "Si" : "No";
            $row->compro = $clientes[$i]->isCliente == 1 ? "Si" : "No";
            $row->username = $clientes[$i]->username;
            $row->registro = $clientes[$i]->fechaInicio;
            $row->nombre = $clientes[$i]->nombre;
            $row->apellido = $clientes[$i]->apellido;
            $row->email = $clientes[$i]->email == null ? $clientes[$i]->emailAux : $clientes[$i]->email;
            $row->telefono = $clientes[$i]->telefono;
            $row->razonSocial = $clientes[$i]->razonSocial;
            $row->cuit = $clientes[$i]->cuit;
            $row->direccionComercial = $clientes[$i]->direccion;
            $row->direccionEntrega = $clientes[$i]->direccionEntrega;
            //$row->ciudad = $clientes[$i]->;
            //$row->cp = $clientes[$i]->;
            //$row->provincia = $clientes[$i]->;
            //$row->pais = $clientes[$i]->;
            array_push($arrRow,$row);
        }
        return $arrRow;
    }
      public function clientes_export_excel(){
        $arr_clientes = Cliente::all();
        
        $clientes = $this->exportCliente($arr_clientes);
        
        //Excel::store(new MultiSheetsExport($clientes), 'clientes.xlsx', null,null);
        return Excel::download(new MultiSheetsExport($clientes), 'clientes.xlsx');
        
        
    }
    
}
