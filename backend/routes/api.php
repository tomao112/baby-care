<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChildController;

// 認証関連のルート（トークン不要）
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 認証が必要なAPIルート
Route::middleware('auth:sanctum')->group(function () {
    // ユーザー情報
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // 子ども情報のCRUD
    Route::apiResource('children', ChildController::class);
});
