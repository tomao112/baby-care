<?php

namespace App\Http\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Auth\UserAlreadyExistsException;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // ログイン
    public function login(LoginRequest $request)
    {
        // 1. メソッドの最初でリクエストの内容を確認
        try {
            // エラーの詳細をログに出力
            Log::info('Login attempt', ['email' => $request->email]);

            $result = $this->authService->login(
                $request->email,
                $request->password
            );

            return response()->json($result);
        } catch (\Exception $e) {
            // エラーの詳細をログに出力
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'ログインに失敗しました',
                'error' => $e->getMessage()  // 開発環境でのみ表示
            ], 500);
        }
    }

    // ユーザー登録
    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());
            return response()->json($result, 201);
        } catch (UserAlreadyExistsException $e) {
            return response()->json([
                'message' => 'このメールアドレスは既に登録されています'
            ], 422);
        }
    }

    // ログアウト
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'ログアウトしました']);
    }


    // 現在のユーザー情報を取得
    public function user(Request $request)
    {
        return response()->json($this->authService->getCurrentUser($request->user()));
    }

    /**
     * ユーザーの情報を更新
     */
    public function update(UpdateUserRequest $request)
    {
        try {
            $user = $this->authService->updateUser($request->user(), $request->validated());
            return response()->json([
                'user' => $user,
                'message' => 'プロフィール更新成功',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => '更新に失敗しました'], 500);
        }
    }
}
