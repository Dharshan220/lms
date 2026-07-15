<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !$request->user()->is_active) {
            abort(403, 'Unauthorized.');
        }

        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}
