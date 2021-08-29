<?php

namespace App\Services;

use App\Repositories\Interfaces\DeviceRepositoryInterface;

class DeviceService
{
    private $repo;

    public function __construct(DeviceRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return $this->repo->all();
    }

    public function store($attubites)
    {
        return $this->repo->create($attubites);
    }
}
