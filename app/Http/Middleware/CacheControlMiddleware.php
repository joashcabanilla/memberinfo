<?php

namespace App\Http\Middleware;

use Closure;

class CacheControlMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $response->header('Cache-Control','must-revalidate');

        return $response;
    }
}