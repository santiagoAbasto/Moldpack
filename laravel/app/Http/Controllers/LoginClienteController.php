<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Cliente;
use App\Models\Logo;
use App\Models\Rede;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Contacto;
use App\Mail\ClienteRegistroPendiente;

class LoginClienteController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //Cambiar ruta a donde se va a logear el cliente
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function username(){
        return 'username';
    }

    public function __construct()
    {
        //$this->middleware('auth.cliente')->except('logout');
    }

    public function login(Request $request) {

        
        $request->session()->forget('obj_fila');
        
        $this->validateLogin($request);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        
        $cliente = Cliente::where('username','=',$request->username)->first();
        if($cliente && $this->passwordMatches($cliente, $request->password)) {
            if(Auth::guard('cliente')->check()) {
                Auth::guard('cliente')->logout();
            }

            Auth::guard('cliente')->loginUsingId($cliente->id);

            if(Auth::guard('cliente')->user()->estado == 1){
                $request->session()->regenerate();
                $this->clearLoginAttempts($request);
                $this->upgradeLegacyPassword($cliente, $request->password);
                
                $contacto = Contacto::first();
                return redirect()->route('page.pedido');
            }else{
                Auth::guard('cliente')->logout();
                return back()->withErrors(['msj' => "Datos incorrectos"]);
            }
                
        } else{            
            $this->incrementLoginAttempts($request);
            
            return back()->withErrors(['msj' => "Datos incorrectos"]);
        }
    }

    private function passwordMatches(Cliente $cliente, string $password): bool
    {
        if (Hash::check($password, $cliente->password)) {
            return true;
        }

        return hash_equals((string) $cliente->password, $password);
    }

    private function upgradeLegacyPassword(Cliente $cliente, string $password): void
    {
        if (Hash::check($password, $cliente->password)) {
            return;
        }

        $cliente->password = Hash::make($password);
        try { $cliente->password_encrypted = Crypt::encryptString($password); } catch (\Exception $e) {}
        $cliente->save();
    }
    public function salir(Request $request) {        
        Auth::guard('cliente')->logout();

        if ($request->hasSession()) {
            $request->session()->forget([
                'obj_fila',
                'admin_impersonating_cliente_id',
                'admin_impersonating_cliente_by',
            ]);
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $redirect = redirect()->route('page.inicio');

        if ($request->boolean('timeout')) {
            $redirect = $redirect->withErrors([
                'sesion' => 'La sesion expiro. Inicie sesion nuevamente.',
            ]);
        }

        return $redirect->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }

        /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard('cliente')->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard('cliente')->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */  

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard('cliente')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('cliente');
    }

    public function registro_cliente()
    { 
        $active = 'page.registro';
        $contactos = Contacto::orderBy('orden', 'ASC')->get();
        $redes = Rede::get();
        $logosheader = Logo::where('seccion', 'header')->orderBy('id', 'ASC')->get();
        $logosfooter = Logo::where('seccion', 'footer')->first();

       return view('page.registro', compact('logosheader','logosfooter', 'contactos', 'active','redes'));
    }
    
    public function registro_cliente_post(request $request)
    {
        
           $request->validate([
            'username' => 'required|unique:clientes,username',
            'email' => 'required|email|unique:clientes,email',
            'password' => 'required|string|min:6',
          ], [
            'username.required' => 'Ingrese un usuario.',
            'username.unique' => 'Este usuario ya esta registrado.',
            'email.required' => 'Ingrese un email.',
            'email.email' => 'Ingrese un email valido.',
            'email.unique' => 'Este email ya esta registrado.',
            'password.required' => 'Ingrese una contrasena.',
            'password.min' => 'La contrasena debe tener al menos 6 caracteres.',
          ]);

        $user = new cliente;
        $passwordPlano = (string) $request->password;
        
          $user->username = $request->username;
          $user->password = Hash::make($passwordPlano);
          try { $user->password_encrypted = Crypt::encryptString($passwordPlano); } catch (\Exception $e) {}
          $user->email = $request->email;   
          $user->descuento = 0;
          $user->rol = '';
          $user->estado = 0;
          $user->save();

          $contactoRegistro = Contacto::first();
          $mensajeRegistro = 'Tu registro se ha completado con exito. En las proximas 24 hs nos contactaremos contigo via mail para la activacion de la pagina.';

          try {
              Mail::to($user->email)->send(new ClienteRegistroPendiente($user, $contactoRegistro));
          } catch (\Exception $e) {
              \Log::warning('No se pudo enviar el aviso de registro pendiente al cliente.', [
                  'cliente_id' => $user->id,
                  'email' => $user->email,
                  'error' => $e->getMessage(),
              ]);
          }

          $copias = Contacto::get()->flatMap(function ($contacto) {
              return [
                  $contacto->correo ?? null,
                  $contacto->correoF ?? null,
              ];
          })->push('ventas@moldpack.com.ar')->filter(function ($email) {
              return filter_var($email, FILTER_VALIDATE_EMAIL);
          })->unique()->values();

          foreach ($copias as $correo) {
              try {
                  Mail::to($correo)->send(new ClienteRegistroPendiente($user, $contactoRegistro));
              } catch (\Exception $e) {
                  \Log::warning('No se pudo enviar el aviso interno de registro pendiente.', [
                      'cliente_id' => $user->id,
                      'email_destino' => $correo,
                      'error' => $e->getMessage(),
                  ]);
              }
          }

          return redirect()->route('page.registro')->with('success', $mensajeRegistro);
    }

   
}
