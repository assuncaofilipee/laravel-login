<?php

namespace App\Services;

use App\Models\Profile;
use App\Repositories\ProfileRepository;

class ProfileService
{
    private $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): Profile
    {
        return $this->repository->create($data);
    }
}
