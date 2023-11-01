<?php

namespace App\Providers;

use App\API\Slack\Concretes\SlackApi;
use App\API\Slack\Contracts\SlackApiInterface;
use App\API\Twitter\Concretes\V2\TwitterApi;
use App\API\Twitter\Contracts\TwitterApiInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        TwitterApiInterface::class => TwitterApi::class,
        SlackApiInterface::class => SlackApi::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
