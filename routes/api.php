<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticleController;

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


/* User Route */

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// Route::Post('createRole', [RoleController::class, 'createRole']);

Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::put('update', 'update');
        Route::put('reset-password', 'resetPassword');
        Route::delete('destroy', 'destroy');
    });
});











/* Article Route */

Route::apiResource('articles', ArticleController::class);
Route::get('articles/search/{searching}', [ArticleController::class, 'search']);




//   Category Route

Route::apiResource('categories', CategoryController::class);









/* Tag Route */

Route::apiResource('tags', TagController::class);












//   Comment Route

// Route::apiResource('comments', CommentController::class)->middleware('auth:api');

Route::controller(CommentController::class)->group(function () {
    Route::get('comments', 'index');
    Route::post('comments', 'store');
    Route::get('comments/{comment}', 'show');
    // Route::put('comments/{comment}', 'update');
    Route::delete('comments/{comment}', 'destroy');
    // Route::get('comments/article/{comment}', 'showComments');
})->middleware('auth:api');
