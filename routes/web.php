<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SearchTermsController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\TwitterApiController;
use App\Http\Controllers\DashboardController;
use App\Jobs\SearchForKeywordTweets;
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
   return redirect()->route('login');
});

Route::prefix('twitter')
    ->name('twitter.')
    ->group(function () {
        Route::get('authorize', [TwitterApiController::class, 'authorizeUrl'])->name('authorize');
        Route::get('callback', [TwitterApiController::class, 'callback'])->name('callback');
    });

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')
    ->group(function () {
        Route::resource('tweets', TweetsController::class)
            ->except('create', 'store');
        Route::post('tweets/{tweet}/reply', [TweetsController::class, 'reply'])->name('tweets.reply');
        Route::resource('search-terms', SearchTermsController::class);
        Route::post('delete-reply/{reply}', [SearchTermsController::class, 'deleteReply'])->name('delete-reply');
        Route::post('search-terms/{search_term}/search', [SearchTermsController::class, 'search'])->name('search-terms.search');
    });


Route::get('test', function () {
    $searchTerm = \App\Models\SearchTerm::find(1);
    dispatch_sync(new SearchForKeywordTweets($searchTerm));
});
