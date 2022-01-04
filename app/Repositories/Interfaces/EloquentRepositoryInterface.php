<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    public function all(array $columns = ['*'], array $conditions = []): Collection;

    public function create(array $attributes): Model;

    public function find(int $id): ?Model;

    public function update(Model $model, array $attributes): Model;
}
