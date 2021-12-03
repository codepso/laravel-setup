<?php

namespace Codepso\Laravel\Providers;

use Codepso\Laravel\Middleware\RequestToSnakeCase;
use Codepso\Laravel\Middleware\ResponseToCamelCase;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class CodepsoServiceProvider extends ServiceProvider
{
    public function boot(Kernel $kernel)
    {
        $kernel->appendMiddlewareToGroup('api', RequestToSnakeCase::class);
        $kernel->appendMiddlewareToGroup('api', ResponseToCamelCase::class);

        // Publish
        $this->publishes([
            __DIR__.'/../../config/codepso.php' => config_path('codepso.php'),
        ], 'codepso-config');
    }
}
