<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSchoolActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->school_id && !$user->school?->is_active) {
            abort(403, 'Your school account has been deactivated.');
        }
        return $next($request);
    }
}
