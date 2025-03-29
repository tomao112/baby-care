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

    // public function updateLastLogin(int $userId): void
    // {
    //     User::where('id', $userId)->update([
    //         'last_login_at' => now(),
    //         'login_attempts' => 0
    //     ]);
    // }

    // public function incrementLoginAttempts(int $userId): void
    // {
    //     User::where('id', $userId)->increment('login_attempts');
    //     User::where('id', $userId)->update([
    //         'last_attempt_at' => now()
    //     ]);
    // }

    // public function resetLoginAttempts(int $userId): void
    // {
    //     User::where('id', $userId)->update([
    //         'login_attempts' => 0,
    //         'last_attempt_at' => null
    //     ]);
    // }
}
