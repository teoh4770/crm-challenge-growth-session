<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdminMiddleware
{
    // role:admin being as a shortcut

    public function handle(Request $request, Closure $next)
    {
        // assert that user is admin
        if($request->user()->hasRole('admin')) {
            return $next($request);
        }
         abort(403);
    }
}
