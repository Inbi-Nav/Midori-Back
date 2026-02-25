<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'No autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        if (!in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'Acceso denegado. Se requiere uno de estos roles: ' . implode(', ', $roles)
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}