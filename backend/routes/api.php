<?php

use Illuminate\Support\Facades\Route;
use App\Http\Api\Children\ChildController;
use App\Http\Api\Children\Record\GrowthRecordController;
use App\Http\Api\Auth\AuthController;

// 認証関連のルート（トークン不要）
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 認証が必要なAPIルート
Route::middleware('auth:sanctum')->group(function () {
    // ユーザー情報
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/update', [AuthController::class, 'update']);

    // 子ども情報のCRUD
    Route::apiResource('children', ChildController::class);
    Route::get('children/{childId}/growth-records', [GrowthRecordController::class, 'getByChild']);
    Route::post('/growth-records', [GrowthRecordController::class, 'edit']);
    Route::put('/growth-records/{id}', [GrowthRecordController::class, 'update']);

    // 育児記録
    Route::get('/children/{childId}/daily-records', \App\Http\Api\Children\Record\Daily\Controllers\ShowAction::class);
    Route::post('/children/{childId}/daily-records', \App\Http\Api\Children\Record\Daily\Controllers\IndexAction::class);
});
