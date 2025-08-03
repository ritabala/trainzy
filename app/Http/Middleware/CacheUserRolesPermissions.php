<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class CacheUserRolesPermissions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (user()) {
            $user = user();
            // Only create cache if it doesn't exist
            if (!Cache::has("user_roles_{$user->id}") || !Cache::has("user_permissions_{$user->id}")) {
                $user->cacheRolesPermissions();
            }
        }

        return $next($request);
    }
} 