<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware('api')->group(function () {
    Route::apiResource('products', ProductController::class);
    
    // Test route
    Route::get('/test', function () {
        return ['message' => 'API is working!'];
    });
});