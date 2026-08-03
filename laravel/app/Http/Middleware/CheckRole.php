<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $allowedRoles = array_map('strval', $roles);

        if (!in_array((string) $user->role, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
