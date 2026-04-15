<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            abort(403, 'Unauthorized. You do not have access to this area.');
        }

        return $next($request);
    }
}
