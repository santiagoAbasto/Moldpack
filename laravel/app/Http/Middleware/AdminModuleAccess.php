<?php

namespace App\Http\Middleware;

use App\Support\AdminModules;
use Closure;
use Illuminate\Http\Request;

class AdminModuleAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $path = trim($request->path(), '/');
        $routeName = optional($request->route())->getName();

        if (AdminModules::routeAllowed($user, $path, $routeName)) {
            return $next($request);
        }

        abort(403);
    }
}
