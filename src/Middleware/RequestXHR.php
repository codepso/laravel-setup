<?php

namespace Codepso\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestXHR
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        return $next($request);
    }
}
