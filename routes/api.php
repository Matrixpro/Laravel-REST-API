<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/token', [TokenController::class, 'create']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('product', ProductController::class);
});
