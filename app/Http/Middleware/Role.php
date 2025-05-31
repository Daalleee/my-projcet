<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
  public function handle(Request $request, Closure $next, $role)
  {
    if ($request->user()->role !== $role) {
      abort(403, 'Unauthorized');
    }
    return $next($request);
  }
}
