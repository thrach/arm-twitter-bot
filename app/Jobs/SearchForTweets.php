<?php

namespace App\Jobs;

use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Models\SearchTerm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchForTweets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(TwitterApiInterface $twitterApi): void
    {
        SearchTerm::each(function (SearchTerm $searchTerm) use ($twitterApi){
            dispatch(new SearchForKeywordTweets($searchTerm));
        });
    }
}
