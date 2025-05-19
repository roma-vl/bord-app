<?php

namespace App\Parents\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

abstract class Repository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    abstract protected function getModelClass(): string;

    /**
     * @return Model|Application|mixed
     */
    protected function startConditions(): mixed
    {
        return clone $this->model;
    }
}
