<?php

use App\Http\Controllers\AddressVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;


Route::middleware('api')->group(function () {
    Route::apiResource('products', ProductController::class);
    // Test route
    Route::get('/test', function () {
        return ['message' => 'API is working!'];
    });
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    

});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// routes/api.php
Route::post('/verify-address', [AddressVerificationController::class, 'verify']);