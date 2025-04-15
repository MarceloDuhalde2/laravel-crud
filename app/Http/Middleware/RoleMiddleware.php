<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verificar si el usuario está autenticado
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        // Obtener el usuario autenticado con tipado explícito
        /** @var User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        // Verificar si el usuario tiene el rol especificado
        if ($user->hasRole($role)) {
            return $next($request);
        }

        abort(403, 'Acceso no autorizado.');
    }
}