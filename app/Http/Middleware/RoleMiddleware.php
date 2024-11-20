<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole($role)) {
            return redirect()->route('/login')->with('error', 'No tienes permiso para acceder a esta página');
        }

        return $next($request);
    }
}
