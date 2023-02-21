<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoleController;
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


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::Post('createRole', [RoleController::class,'createRole']);

Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::put('update','update');
        Route::put('reset-password', 'resetPassword');
        Route::delete('destroy','destroy');
    });
});
/* User Route */











/* Article Route








 */





//   Category Route



Route::apiResource('categories', CategoryController::class);



// Tag Route



Route::apiResource('tags', CategoryController::class);



 /* Comment Route








 */
