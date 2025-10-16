<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
   public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! $user->is_admin) {
            // Option 1: redirect to home
            return redirect('/');

            // Option 2: return 403 forbidden
            // abort(403);
        }

        return $next($request);
    }
}
