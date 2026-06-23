<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperuserRequired
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::user()?->superuser) {
            abort(403, 'Superuser access required.');
        }

        return $next($request);
    }
}
