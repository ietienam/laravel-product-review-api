<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/me', [AuthController::class, 'me']);
Route::get('/me/products', [UserController::class, 'myProducts']);

Route::get('/users/{user}/products', [UserController::class, 'userProducts']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);

Route::patch('/products/{product}', [ProductController::class, 'update']);
Route::patch('/products/reviews/{review}', [ReviewController::class, 'update']);

Route::apiResource('products', ProductController::class)
    ->only('index', 'show', 'store', 'destroy');
Route::apiResource('products/{product}/reviews', ReviewController::class)
    ->only('store', 'destroy');
