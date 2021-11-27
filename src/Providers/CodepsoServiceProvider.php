<?php

namespace Codepso\Laravel\Providers;

use Codepso\Laravel\Middleware\RequestToSnakeCase;
use Codepso\Laravel\Middleware\ResponseToCamelCase;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class CodepsoServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        // Middleware
        $router->middlewareGroup('api', [
            RequestToSnakeCase::class,
            ResponseToCamelCase::class,
        ]);

        // Publish
        $this->publishes([
            __DIR__.'/../../config/codepso.php' => config_path('codepso.php'),
        ], 'codepso-config');
    }
}
