<?php

namespace App\Repositories\Eloquents;

use App\Models\Analysis;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class AnalysisRepository extends BaseRepository implements AnalysisRepositoryInterface
{
    protected $model;

    /**
     * AnalysisRepository constructor.
     *
     * @param Analysis $model
     */
    public function __construct(Analysis $model)
    {
        $this->model = $model;
    }

    public function createManyFiles(Model $model, array $files): Model
    {
        $model->files()->createMany($files);
        return $model;
    }

    public function createManyExploits(Model $model, array $exploits): Model
    {
        $model->exploitsLogs()->createMany($exploits);
        return $model;
    }

    public function createManyCreds(Model $model, array $creds): Model
    {
        $model->credsLogs()->createMany($creds);
        return $model;
    }

    public function createManyStatic(Model $model, string $path, array $attributes): Model
    {
        $model
            ->files()
            ->where('path', $path)
            ->first()
            ->staticLogs()
            ->create($attributes);
        return $model;
    }
}
