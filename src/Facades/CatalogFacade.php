<?php

namespace Codepso\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class CatalogFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'Catalog';
    }
}
