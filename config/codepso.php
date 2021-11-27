<?php

use Codepso\Laravel\Catalog\Config;

return [
    'catalog' => [
        'size' =>  env('CATALOG_SIZE', Config::CATALOG_SIZE),
    ]
];
