<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DepartmentAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $dptid = (int) $request->route('dptid');

        if (! $dptid) {
            abort(404);
        }

        if (Auth::user()->superuser) {
            return $next($request);
        }

        if ((int) Auth::user()->department_id === $dptid) {
            return $next($request);
        }

        abort(403, 'You do not have access to this department.');
    }
}
