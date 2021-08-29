<?php

namespace App\Repositories\Eloquents;

use App\Models\Device;
use App\Repositories\Interfaces\DeviceRepositoryInterface;

class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{
    protected $model;

    /**
     * DeviceRepository constructor.
     *
     * @param Device $model
     */
    public function __construct(Device $model)
    {
        $this->model = $model;
    }
}
