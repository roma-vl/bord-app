<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    protected Builder $builder;
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach (array_filter($this->data, fn($value) => $value !== null && $value !== '') as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

}
