<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !in_array($user->systemRole, $roles)) {
            return response()->json([
                'message' => 'access denied'
            ], 403);
        }

        return $next($request);
    }
}
