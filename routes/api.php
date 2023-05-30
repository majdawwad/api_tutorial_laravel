<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

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

Route::group(['middleware' => ['api', 'checkPassword', 'changeLanguage']], function ($route) {
    Route::post('get-main-categories', [CategoryController::class, 'index']);
    Route::post('get-category', [CategoryController::class, 'show']);

    Route::group(['prefix' => 'admin'], function ($route) {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth.guard:admin-api']);
        Route::post('get-main-categories', [CategoryController::class, 'index'])->middleware(['auth.guard:admin-api']);
        Route::post('profile', function () {
            return \Illuminate\Support\Facades\Auth::user();
        })->middleware(['auth.guard:admin-api']);;
    });

    Route::group(['prefix' => 'user'], function ($route) {
        Route::post('login', [AuthController::class, 'userLogin']);
    });

    Route::group(['prefix' => 'user', 'middleware' => 'auth.guard:user-api'], function () {
        Route::post('profile', function () {
            return \Illuminate\Support\Facades\Auth::user();
        });
    });
});

// Route::group(['middleware' => ['api', 'checkPassword', 'changeLanguage', 'auth.guard:admin-api']], function ($route) {
//     Route::post('get-main-categories', [CategoryController::class, 'index']);
// });
