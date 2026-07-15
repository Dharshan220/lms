<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next, $returnView = false)
    {
        if ($request->user() && !$request->user()->hasVerifiedEmail()) {
            return $returnView ? view('auth.verify-email') : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
