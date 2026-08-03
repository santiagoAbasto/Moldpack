<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class middelewarecliente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('cliente')->check()) {
           
            return $next($request);
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'La sesion expiro. Inicie sesion nuevamente.',
                'redirect' => route('page.inicio'),
            ], 401);
        }

        return redirect()->route('page.inicio')->withErrors([
            'sesion' => 'La sesion expiro. Inicie sesion nuevamente.',
        ]);
       
    }
}
