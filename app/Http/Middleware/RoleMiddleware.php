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
            return response()->json(['message' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        if (!in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'Access denied. Requires one of these roles: ' . implode(', ', $roles)
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}