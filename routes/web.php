<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/login/vk', [\App\Auth\Controllers\SocialLoginController::class, 'callbackVK']);
Route::get(
    '/social/vk',
    function (\Illuminate\Http\Request $request) {
        // dd($request->header('referer')); 
        session(['redirect_to' => $request->header('referer')]);
        return Socialite::with('vkontakte')->redirect();
    }
);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum', 'verified'], function() {
    Route::resources([
        'users' => \App\Blog\Controllers\Admin\UsersController::class,
        'themes' => \App\Blog\Controllers\Admin\ThemeController::class,
    ]);
});
