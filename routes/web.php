<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TwitterApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');

Route::prefix('twitter')
    ->name('twitter.')
    ->group(function () {
        Route::get('authorize', [TwitterApiController::class, 'authorizeUrl'])->name('authorize');
        Route::get('callback', [TwitterApiController::class, 'callback'])->name('callback');
    });


