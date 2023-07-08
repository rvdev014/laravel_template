<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function findByEmail(string $email): ?User
    {
        /** @var User $user */
        $user = User::query()
            ->where('email', $email)
            ->firstOrFail();

        return $user;
    }
}
