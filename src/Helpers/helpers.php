<?php

use Codepso\Laravel\Helpers\ApiLogHelper;
use Codepso\Laravel\Helpers\ApiRenderHelper;

if (!function_exists('apiRender')) {
    function apiRender(): ApiRenderHelper
    {
        return new ApiRenderHelper;
    }
}

if (!function_exists('apiLog')) {
    function apiLog(): ApiLogHelper
    {
        return new ApiLogHelper;
    }
}
