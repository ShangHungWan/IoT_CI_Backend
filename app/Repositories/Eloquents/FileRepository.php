<?php

namespace App\Repositories\Eloquents;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;

class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    protected $model;

    /**
     * FileRepository constructor.
     *
     * @param File $model
     */
    public function __construct(File $model)
    {
        $this->model = $model;
    }
}
