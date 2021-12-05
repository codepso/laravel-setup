<?php

namespace Codepso\Laravel\Providers;

use Codepso\Laravel\Middleware\RequestToSnakeCase;
use Codepso\Laravel\Middleware\ResponseToCamelCase;
use Codepso\Laravel\Middleware\ValidateToken;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;

class CodepsoServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function boot(Kernel $kernel)
    {
        $kernel->appendMiddlewareToGroup('api', RequestToSnakeCase::class);
        $kernel->appendMiddlewareToGroup('api', ResponseToCamelCase::class);
        $kernel->appendMiddlewareToGroup('api', ResponseToCamelCase::class);

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('validate.token', ValidateToken::class);

        // Publish
        $this->publishes([
            __DIR__.'/../../config/codepso.php' => config_path('codepso.php'),
        ], 'codepso-config');
    }
}
