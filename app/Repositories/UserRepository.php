<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create($data): User
    {
        $data['password'] = bcrypt($data['password']);
        return $this->user::create($data);
    }

    public function all(): Collection
    {
        return User::all();
    }

    public function show($id): User
    {
        return $this->user->findOrFail($id);
    }

    public function update($id, $data): User
    {
        $user = $this->user->findOrFail($id);
        $user->updateOrFail($data);
        return $user;
    }

    public function delete($id): void
    {
        $this->user->delete();
    }
}
