<?php

namespace App\Console\Commands;

use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Models\TweetReply;
use Illuminate\Console\Command;

class SyncTweetsStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-tweets-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(public readonly TwitterApiInterface $twitterApi)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        TweetReply::where('last_synced_at', '<', now()->subHour())
            ->orWhere('last_synced_at', null)
            ->chunk(100, function ($replies) {
                $response = $this->twitterApi->stats($replies->pluck('twitter_post_id')->join(','));

                $response->publicMetrics()
                    ->each(function ($item) {
                        foreach($item as $tweetId => $publicStatus) {
                            TweetReply::where('twitter_post_id', $tweetId)
                                ->update($publicStatus);
                        }
                    });
            });
    }
}
