<?php

namespace App\Services;

use App\Models\User;

class TokenService
{
    private string $defaultTokenName = 'auth_token';

    public function createToken(User $user, ?string $name = null): string
    {
        $tokenName = $name ?? $this->defaultTokenName;
        return $user->createToken($tokenName)->plainTextToken;
    }

    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
