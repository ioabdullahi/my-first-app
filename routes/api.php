<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;


Route::middleware('api')->group(function () {
    Route::apiResource('products', ProductController::class);
    // Test route
    Route::get('/test', function () {
        return ['message' => 'API is working!'];
    });
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
