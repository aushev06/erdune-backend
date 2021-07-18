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
        return $request->user();
    }
);


Route::group(
    ['auth:sanctum'],
    function () {
        Route::resource('posts', \App\Blog\Controllers\PostController::class);

        Route::options('posts/image-by-url', function () {
            return "ok";
        });

        Route::post('posts/image-by-url', [\App\Blog\Controllers\PostController::class, 'saveByUrl']);
    }
);
