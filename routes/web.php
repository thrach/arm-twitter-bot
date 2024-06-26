<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ForeignAidController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SearchTermsController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\TwitterApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TwitterUsersController;
use App\Http\Middleware\HasVerifiedPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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

Route::get('/', LandingPageController::class);
Route::post('check-password', function (Request $request) {
    if ($request->filled('password') && $request->password === config('services.pages.password')) {
        Cookie::queue('password_step_passed', 'true', 60 * 24 * 30);

        return redirect()->to('/social');
    }

    return redirect()->back();
});

Route::middleware(HasVerifiedPassword::class)
    ->group(function () {
        Route::get('social', SocialController::class);
        Route::get('foreign-aid', ForeignAidController::class);
    });
Route::get('thank-you', function () {
    if (session()->get('twitter_authorized')) {
        return view('thank-you');
    }
    return redirect()->to('/');
})->name('thank-you');

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
        Route::resource('twitter-users', TwitterUsersController::class)
            ->only('index', 'show', 'update');
    });
