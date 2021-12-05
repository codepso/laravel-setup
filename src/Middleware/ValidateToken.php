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
        if ($user === null || $user->is_active !== 1) {
            return response()->json(['message' => 'User not found'], 401);
        }
        return $next($request);
    }
}
