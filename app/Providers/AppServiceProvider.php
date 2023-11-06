<?php

namespace App\Providers;

use App\API\Twitter\Concretes\V2\TwitterApi;
use App\API\Twitter\Contracts\TwitterApiInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        TwitterApiInterface::class => TwitterApi::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        URL::forceScheme('https');

        Http::macro('sentiment', function () {
            $config = config('services.google.sentiment');

            return Http::baseUrl($config['base_uri'])
                ->withHeaders([
                    "Authorization" => "Bearer {$config['api_token']}",
                    "Content-Type" => "application/json"
                ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        View::share('leftNavItems', [
            [
                'name' => 'Tweets',
                'url' => 'tweets',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                              <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                           </svg>',
            ],
            [
                'name' => 'Search Terms',
                'url' => 'search-terms',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                               <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                           </svg>',
            ],
        ]);
    }
}
