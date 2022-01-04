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

    public function index(array $conditions = ['is_os_less' => '0'])
    {
        return $this->repo->all(['*'], $conditions);
    }

    public function store($attubites)
    {
        return $this->repo->create($attubites);
    }
}
