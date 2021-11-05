<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get(
    '/user',
    function (Request $request) {
        return array_merge($request->user()->toArray(), ['token' => $request->user()->createToken('auth_token')->plainTextToken]);
    }
);


Route::group(
    ['auth:sanctum'],
    function () {
        Route::resource('posts', \App\Blog\Controllers\PostController::class)->only(['store', 'update', 'destroy']);

        Route::options('posts/image-by-url', function () {
            return "ok";
        });

        Route::post('posts/image-by-url', \App\Blog\Actions\SaveImageAction::class);
        Route::patch('/user/{user}', \App\Blog\Actions\ProfileAction::class);
        Route::post('/likes/', [\App\Blog\Controllers\LikeController::class, 'like']);
    }
);


Route::get('users/categories', [\App\Blog\Controllers\UsersController::class, 'categories']);
Route::get('posts/themes', [\App\Blog\Controllers\PostController::class, 'getThemes']);
Route::resource('posts', \App\Blog\Controllers\PostController::class)->only(['index', 'show']);
Route::resource('users', \App\Blog\Controllers\UsersController::class)->only(['index', 'show']);
Route::get('posts/{post}/comments', [\App\Blog\Controllers\CommentController::class, 'show']);
Route::apiResource('comments', \App\Blog\Controllers\CommentController::class)->only(['index', 'destroy', 'store', 'update']);
Route::apiResource('categories', \App\Blog\Controllers\CategoryController::class);
Route::apiResource('directories', \App\Blog\Controllers\DirectoryController::class)->only(['index']);
