<?php

namespace App\Repositories;

use App\Models\Profile;
class ProfileRepository
{
    private $profile;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function create($data)
    {
        return $this->profile::create(array_merge(
            $data,['user_id' => auth()->user()->id]
        ));
    }
}
