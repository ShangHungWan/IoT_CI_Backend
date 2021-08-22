<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class AuthService
{
    private $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create($attrributes)
    {
        $attrributes['password'] = bcrypt($attrributes['password']);
        $attrributes['type'] = 'user';

        return $this->repo->create($attrributes);
    }
}
