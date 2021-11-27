<?php

namespace Codepso\Laravel\Middleware;

use Illuminate\Support\Str;

abstract class ConvertToCase
{
    public const CASE_SNAKE = 'snake';
    public const CASE_CAMEL = 'camel';

    /**
     * Convert an array to a given case.
     *
     * @param string $case
     * @param $data
     * @return array
     */
    public function convertKeysToCase(string $case, $data): array
    {
        if (!is_array($data)) {
            return $data;
        }

        $array = [];

        foreach ($data as $key => $value) {
            $array[Str::{$case}($key)] = is_array($value)
                ? $this->convertKeysToCase($case, $value)
                : $value;
        }

        return $array;

    }
}
