<?php

use Codepso\Laravel\Helpers\ApiRenderHelper;

if (!function_exists('apiRender')) {
    function apiRender(): ApiRenderHelper
    {
        return new ApiRenderHelper;
    }
}
