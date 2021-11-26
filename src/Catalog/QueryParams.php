<?php

namespace Codepso\Laravel\Catalog;

class QueryParams
{
    public array $filters;
    public array $customFilters;
    public array $includes;
    public array $sort;
    public string $render;
    public bool $nested;
    public int $perPage;
}
