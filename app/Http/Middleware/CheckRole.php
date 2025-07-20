<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole extends Middleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!in_array($request->user()->roleUser, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
