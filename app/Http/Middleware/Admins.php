<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->id_rol == 1 || auth()->user()->id_rol == 2) {
            return $next($request);
        }
        return response()->json(['error' => 'No tines permiso para esta acción'], 401);
    }
}
