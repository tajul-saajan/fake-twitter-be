<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UserController::class, 'search']);
        Route::get('{user}/profile', [UserController::class, 'profile']);

        Route::post('{user}/follow', [UserController::class, 'follow']);
        Route::post('{user}/unfollow', [UserController::class, 'unfollow']);
    });

    Route::group(['prefix' => 'tweets'], function () {
        Route::post('', [TweetController::class, 'store']);
        Route::post('{tweet}/react', [TweetController::class, 'react']);
    });

    Route::group(['prefix' => 'timeline'], function () {
        Route::get('', [TimelineController::class, 'index']);
    });
});
