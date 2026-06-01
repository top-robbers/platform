<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ServerStatusController;
use App\Http\Middleware\ApiTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);

    Route::middleware(ApiTokenMiddleware::class)->group(function () {
        Route::get('/server/status', [ServerStatusController::class, 'show']);
        Route::post('/server/status/refresh', [ServerStatusController::class, 'refresh']);
    });
});