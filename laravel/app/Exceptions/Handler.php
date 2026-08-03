<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (TokenMismatchException $e, $request) {
            $isAdmin = $request->is('adm/*');

            if ($request->hasSession()) {
                $request->session()->regenerateToken();
            }

            $headers = [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
            ];

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'La sesion expiro. Inicie sesion nuevamente.',
                    'redirect' => $isAdmin ? route('login') : route('page.inicio'),
                ], 419)->withHeaders($headers);
            }

            if ($isAdmin) {
                return redirect()->route('login')->withHeaders($headers);
            }

            return redirect()->route('page.inicio')->withErrors([
                'sesion' => 'La sesion expiro. Inicie sesion nuevamente.',
            ])->withHeaders($headers);
        });
    }
}
