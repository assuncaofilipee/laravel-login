<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return $this->repository->create($data);
    }
}
