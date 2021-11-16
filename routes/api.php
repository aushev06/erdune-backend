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
        return array_merge($request->user('api')->toArray(), ['token' => $request->user('api')->createToken('auth_token')->plainTextToken]);
    }
);

Route::middleware('auth:sanctum')->get('/user/notifications', [\App\Blog\Controllers\UsersController::class, 'getNotifications']);
Route::middleware('auth:sanctum')->get('/user/notifications/read', [\App\Blog\Controllers\UsersController::class, 'readNotifications']);

Route::middleware('auth:sanctum')->group(
    function () {
        Route::resource('posts', \App\Blog\Controllers\PostController::class)->only(['store', 'update', 'destroy']);

        Route::options('posts/upload', function () {
            return "ok";
        });
        Route::post('posts/upload', \App\Blog\Actions\UploadAction::class);

        Route::patch('/user/{user}', \App\Blog\Actions\ProfileAction::class);
        Route::post('/likes', [\App\Blog\Controllers\LikeController::class, 'like']);
    }
);


Route::get('/', [\App\Blog\Controllers\HomeController::class, 'index']);
Route::get('/parse', [\App\Blog\Controllers\HomeController::class, 'parseUrl']);
Route::apiResource('comments', \App\Blog\Controllers\CommentController::class)->only(['index', 'destroy', 'store', 'update']);
Route::apiResource('categories', \App\Blog\Controllers\CategoryController::class);
Route::apiResource('directories', \App\Blog\Controllers\DirectoryController::class)->only(['index']);

Route::resource('users', \App\Blog\Controllers\UsersController::class)->only(['index', 'show']);
Route::get('users/categories', [\App\Blog\Controllers\UsersController::class, 'categories']);
Route::get('users/{id}', [\App\Blog\Controllers\UsersController::class, 'show']);

Route::resource('posts', \App\Blog\Controllers\PostController::class)->only(['index', 'show']);
Route::get('posts/themes', [\App\Blog\Controllers\PostController::class, 'getThemes']);
Route::get('posts/{post}/comments', [\App\Blog\Controllers\CommentController::class, 'show']);

