<?php

namespace App\Nova\Actions;

use App\API\Twitter\Concretes\V2\TwitterApi;
use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Models\TweetReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeleteTweetFromTwitter extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('You can only delete one tweet at a time.');
        }
        /** @var TweetReply $tweetReply */
        $tweetReply = $models->first();

        app()->bind(TwitterApiInterface::class, function () use ($tweetReply) {
            return new TwitterApi($tweetReply->repliedAs->oauthCredential);
        });

        $twitterApi = resolve(TwitterApiInterface::class);

        $twitterApi->deleteTweet($tweetReply->twitter_post_id);

        $tweetReply->update([
            'tweet_deleted_at' => now()
        ]);
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
