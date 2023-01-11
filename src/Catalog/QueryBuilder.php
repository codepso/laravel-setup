<?php

namespace Codepso\Laravel\Catalog;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class QueryBuilder
{
    private Application|Request|string|array|null $request;
    private QueryParams $params;

    function __construct(QueryParams $params)
    {
        $this->request = request();
        $this->params = $params;
    }

    public function getFilters(): array
    {
        $filters = [];
        $values = $this->request->exists('filter') ? $this->request->query('filter') : [];
        foreach ($values as $key => $value) {
            if (!str_contains($key, '.')) {
                $parts = explode(',', $value);
                $filters[$key] = count($parts) > 1 || strpos($key, 'ids') ? $parts : $value;
            } else {
                $keys = explode('.', $key);
                $parentKey = $keys[0];
                $childKey = $keys[1];
                $parts = explode(',', $value);
                $filters[$parentKey][$childKey] = count($parts) > 1 ? $parts : $value;
            }
        }

        return $filters;
    }

    private function getIncludes(): array
    {
        return $this->request->exists('include') ? explode(',', $this->request->query('include')) : [];
    }

    private function getSort(): array
    {
        $sort = [];
        $values = $this->request->exists('sort') ? explode(',', $this->request->query('sort')) : [];
        foreach ($values as $value) {
            if ($value) {
                $sort[] = $this->getSortBy($value);
            }
        }

        if (empty($sort)) {
            $sort[] = ['col' => 'id', 'dir' => 'asc'];
        }

        return $sort;
    }

    private function getSortBy($column): array
    {
        $dir = str_starts_with($column, '-') ? 'desc' : 'asc';
        $col = ($dir === 'desc') ? substr($column, 1) : $column;

        return ['col' => $col, 'dir' => $dir];
    }

    private function getRender(): string
    {
        $render = 'all';
        $renderTypes = ['paginate', 'count', 'all'];
        if ($this->request->exists('render') && in_array($this->request->query('render'), $renderTypes)) {
            $render = $this->request->query('render');
        }

        return $render;
    }

    private function getPerPage(): int
    {
        return $this->request->query('size', config('codepso.catalog.size', Config::CATALOG_SIZE) ?? Config::CATALOG_SIZE);
    }

    private function getNested(): bool
    {
        $nested = $this->request->query('nested', true);
        return $nested === 'true' || $nested === true;
    }

    function getCustomFilters(array $exclude): array
    {
        $customFilters = [];
        if (!empty($exclude)) {
            foreach ($this->params->filters as $column => $value) {
                if (in_array($column, $exclude)) {
                    $customFilters[$column] = $value;
                    unset($this->params->filters[$column]);
                }
            }
        }

        return $customFilters;
    }

    public function params(): QueryParams
    {
        return $this->params;
    }

    public function init(array $exclude): void
    {
        $this->params->filters = $this->getFilters() ?? [];
        $this->params->includes = $this->getIncludes() ?? [];
        $this->params->sort = $this->getSort() ?? [];
        $this->params->perPage = $this->getPerPage() ?? -1;
        $this->params->render = $this->getRender() ?? '';
        $this->params->nested = $this->getNested();
        $this->params->customFilters = $this->getCustomFilters($exclude);
    }
}
