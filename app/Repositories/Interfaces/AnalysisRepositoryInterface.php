<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface AnalysisRepositoryInterface extends EloquentRepositoryInterface
{
    public function createManyFiles(Model $model, array $files): Model;
    public function createManyExploits(Model $model, array $exploits): Model;
    public function createManyCreds(Model $model, array $exploits): Model;
}
