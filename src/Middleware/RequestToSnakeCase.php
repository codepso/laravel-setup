<?php

namespace Codepso\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestToSnakeCase extends ConvertToCase
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
        $request->replace(
            $this->convertKeysToCase(
                self::CASE_SNAKE,
                $request->all()
            )
        );

        return $next($request);
    }
}
