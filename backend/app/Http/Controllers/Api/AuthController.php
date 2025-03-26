<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $authSevice;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // ログイン
    public function login(Request $request)
    {
        try {
            $result = $this->authService->login(
                $request->email,
                $request->password,
            );

            return response()->json($result);
        } catch (invalidCredentialsException $e) {
            return response()->json([
                'message' => 'メールアドレス、パスワードが正しくありません'
            ], 401);
        } catch (AccountLockedException $e) {
            return response()->json([
                'message' => 'アカウントがロックされています'
            ], 423);
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
    public function update(Request $request)
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
