<?php

namespace Codepso\Laravel\Catalog;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Catalog
{
    public Builder $q;
    public QueryBuilder $builder;
    public array $customFilters;

    function __construct(Builder $q = null)
    {
        // app(ClassName::class) returns the service container instance
        $this->builder = app(QueryBuilder::class);
        $this->customFilters = [];
        $this->q = $q;
    }

    public static function for($subject): self
    {
        if (is_subclass_of($subject, Model::class)) {
            $subject = $subject::query();
        }

        return new static($subject);
    }

    /**
     * @param Builder|null $q
     * @return $this
     */
    function init(Builder $q = null): static
    {
        if (!empty($q)) {
            $this->q = $q;
        }
        $this->builder->init($this->customFilters);
        return $this;
    }

    /**
     * @return LengthAwarePaginator|QueryBuilder[]|Collection|int
     */
    function get(): Collection|LengthAwarePaginator|int|array
    {
        $this->addExactFilters()
            ->addIncludes()
            ->addSort();

        $results = match ($this->builder->params()->render) {
            'paginate' => $this->q->paginate($this->builder->params()->perPage)->appends(request()->query()),
            'count' => $this->q->count(),
            default => $this->q->get(),
        };

        if (!$this->builder->params()->nested) {
            return $this->makeWithoutNested($results);
        }

        return $results;
    }

    function makeWithoutNested($results)
    {
        $results = $results->toArray();
        $rows = $results['data'] ?? $results;

        $temp = array_map(function ($row)  {
            foreach ($row as $column => $values) {
                if (is_array($values) && count($values) > 0) {
                    $newColumns = $this->makeColumnsWithoutNested($column, $values);
                    $row = array_merge($row, $newColumns);
                    unset($row[$column]);
                }
            }
            return $row;
        }, $rows);

        if (isset($results['data'])) {
            $results['data'] = $temp;
        } else {
            $results = $temp;
        }

        return $results;
    }

    function makeColumnsWithoutNested($column, $childValues): array
    {
        $newColumns = [];
        foreach ($childValues as $key => $value) {
            if (is_array($value) && count($value) > 0) {
                $newColumns = array_merge($newColumns, $this->makeColumnsWithoutNested($key, $value));
            } else {
                $newColumns[$column . '_' . $key] = $value;
            }
        }

        return $newColumns;
    }

    /**
     * @return $this
     */
    function addSort(): static
    {
        foreach ($this->builder->params()->sort  as $s) {
            $this->q->orderBy($s['col'], $s['dir']);
        }

        return $this;
    }

    function addIncludes(): static
    {
        if ($this->builder->params()->includes) {
            $this->q->with($this->builder->params()->includes);
        }
        return $this;
    }

    /**
     * @return $this
     */
    function addExactFilters(): static
    {
        foreach ($this->builder->params()->filters as $column => $value) {
            if (!is_array($value)) {
                $this->q->where($column, $value);
            } else {
                $this->q->whereIn($column, $value);
            }
        }

        return $this;
    }
}
