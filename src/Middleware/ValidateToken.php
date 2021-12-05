<?php

namespace Codepso\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user === null || $user->status !== 1) {
            abort(401);
        }
        return $next($request);
    }
}
