<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FreePlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario no existe o no tiene rol free, redirigir
        if (!$request->user() || !$request->user()->isFree()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}