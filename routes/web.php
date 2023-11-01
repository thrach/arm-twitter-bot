<?php

use App\Http\Controllers\TwitterApiController;
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

Route::prefix('twitter')
    ->name('twitter.')
    ->group(function () {
        Route::get('authorize', [TwitterApiController::class, 'authorizeUrl'])->name('authorize');
        Route::get('callback', [TwitterApiController::class, 'callback'])->name('callback');
    });

Route::get('test', function () {
    $response = resolve(\App\API\Twitter\Contracts\TwitterApiInterface::class)->stats(1712714911803965815);

    $response->publicMetrics()
        ->each(function ($item) {
            foreach($item as $tweetId => $publicStatus) {
                \App\Models\TweetReply::where('twitter_post_id', $tweetId)
                    ->update($publicStatus);
            }
        });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
