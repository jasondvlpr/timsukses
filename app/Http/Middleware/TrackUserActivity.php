<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Update every 2 minutes to avoid too many DB writes
            $userId = auth()->id();
            $cacheKey = "user_active_{$userId}";

            if (!Cache::has($cacheKey)) {
                auth()->user()->update(['last_active_at' => now()]);
                Cache::put($cacheKey, true, 120); // 2 minutes
            }
        }

        return $next($request);
    }
}
