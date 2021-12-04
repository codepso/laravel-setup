<?php

namespace Codepso\Laravel\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiRenderHelper
{
    function crud(string $model, string $action, int $httpCode = 200): JsonResponse
    {
        $r = ['message' => "$model was $action successfully"];
        return response()->json($r, $httpCode);
    }

    function error(Exception|string $e, int $status = 500): JsonResponse
    {
        $statusCode = ($e instanceof Exception) ? $this->getStatusCode($e->getCode()) : $status;
        $message = ($e instanceof Exception) ? $e->getMessage() : $e;
        $r = ['message' => $message];
        return response()->json($r, $statusCode);
    }

    function success(Exception $e): JsonResponse
    {
        $r = ['message' => $e->getMessage()];
        return response()->json($r);
    }

    private function getStatusCode($statusCode): int
    {
        $httpCode = 500;
        if ($statusCode && is_int($statusCode) && ($statusCode >= 100 && $statusCode < 600)) {
            $httpCode = $statusCode;
        }

        return $httpCode;
    }
}
