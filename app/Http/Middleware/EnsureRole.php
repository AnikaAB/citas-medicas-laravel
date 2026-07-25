<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de control de acceso por rol (RBAC simple).
 * Uso en rutas: ->middleware('rol:admin,recepcionista')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$rolesPermitidos): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! in_array($usuario->rol, $rolesPermitidos, true)) {
            abort(403, 'No tienes permisos para acceder a este recurso.');
        }

        return $next($request);
    }
}
