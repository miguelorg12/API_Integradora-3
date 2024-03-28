<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class active
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
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if (!$user->activated_at) {
            return response()->json(['error' => 'Primero active su cuenta'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['error' => 'Su cuenta ha sido desactivada por los administradores'], 403);
        }
        return $next($request);
    }
}
