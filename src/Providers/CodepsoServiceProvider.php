<?php

namespace Codepso\Laravel\Providers;

use Codepso\Laravel\Middleware\RequestToSnakeCase;
use Codepso\Laravel\Middleware\ResponseToCamelCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class CodepsoServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register()
    {
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('api', RequestToSnakeCase::class);
        $router->pushMiddlewareToGroup('api', ResponseToCamelCase::class);
    }

    public function boot()
    {
        // Middleware
        /*$router->middlewareGroup('api', [
            RequestToSnakeCase::class,
            ResponseToCamelCase::class,
        ]);*/

        // Publish
        $this->publishes([
            __DIR__.'/../../config/codepso.php' => config_path('codepso.php'),
        ], 'codepso-config');
    }
}
