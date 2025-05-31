<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictToUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'user') {
            return $next($request);
        }

        abort(403, 'Unauthorized: Hanya user biasa yang dapat mengakses halaman ini.');
    }
}
