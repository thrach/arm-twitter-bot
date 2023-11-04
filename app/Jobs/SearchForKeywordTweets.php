<?php

namespace App\Jobs;

use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Models\SearchTerm;
use App\Models\Tweet;
use App\Models\SearchTermExclusion;
use App\Models\TwitterUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SearchForKeywordTweets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $excludedKeyWords;

    /**
     * Create a new job instance.
     */
    public function __construct(protected readonly SearchTerm $keywordReply)
    {
        $this->excludedKeyWords = $this->keywordReply
            ->twitterSearchExclusions
            ->pluck('keyword')
            ->map(function ($item) {
                return '-' . $item;
            })->implode(' ');
    }

    /**
     * Execute the job.
     */
    public function handle(TwitterApiInterface $twitterApi): void
    {
        try {
            $response = $twitterApi->searchTweets($this->keywordReply->tags->pluck('name')->join(','), $this->excludedKeyWords);
            $response->tweets()
                ->each(function ($tweet) use ($twitterApi) {
                    if (! Tweet::where('tweet_id', $tweet->id)->exists()) {
                        $user = $twitterApi->detailsOf($tweet->id)->users()->first();
                        $twitterUser = TwitterUser::updateOrCreate([
                            'user_id' => $user->id,
                        ], [
                            'username' => $user->username,
                            'name' => $user->name,
                        ]);

                        $this->keywordReply
                            ->tweets()
                            ->create([
                                'tweet_id' => $tweet->id,
                                'twitter_user_id' => $twitterUser->id,
                                'tweet' => $tweet->text,
                                'reply' => $this->keywordReply->reply,
                            ]);
                    }
                });
        } catch (\Throwable $th) {
            Log::channel('twitter')->error($th->getMessage());
        }
    }
}
