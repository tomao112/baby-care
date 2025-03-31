<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function existsByEmail(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user->fresh();
    }
}
