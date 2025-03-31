<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\TokenService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\UserAlreadyExistsException;
use App\Events\Auth\UserRegistered;
use Illuminate\Support\Facades\DB;

class AuthService
{
    private $userRepository;
    private $tokenService;

    public function __construct(
        UserRepository $userRepository,
        TokenService $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->tokenService = $tokenService;
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        // 既存のトークンを削除
        $this->tokenService->revokeAllTokens($user);

        return [
            'user' => $user,
            'token' => $this->tokenService->createToken($user)
        ];
    }

    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            if ($this->userRepository->existsByEmail($data['email'])) {
                throw new UserAlreadyExistsException();
            }

            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            event(new UserRegistered($user));

            return [
                'user' => $user,
                'token' => $this->tokenService->createToken($user)
            ];
        });
    }

    public function logout(User $user): void
    {
        $this->tokenService->revokeCurrentToken($user);
    }

    public function getCurrentUser(User $user): User
    {
        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        return $this->userRepository->update($user->id, $data);
    }
}