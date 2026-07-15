<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DarkModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $darkMode = false;
        if ($request->user() && $request->user()->dark_mode) {
            $darkMode = true;
        }
        view()->share('darkMode', $darkMode);

        return $next($request);
    }
}
