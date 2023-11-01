<?php

namespace App\Nova\Actions;

use App\API\Twitter\Concretes\V2\TwitterApi;
use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Jobs\ReplyToTweetJob;
use App\Models\Tweet;
use App\Models\TwitterAuthUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ReplyToTweet extends Action
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
        /** @var Tweet $tweet */
        $tweet = $models->first();
        $replyAsId = (int) $fields->get('reply_as');
        /** @var TwitterAuthUser $twitterAuthUser */
        $twitterAuthUser = TwitterAuthUser::find($replyAsId);

        app()->bind(TwitterApiInterface::class, function () use ($twitterAuthUser) {
            return new TwitterApi($twitterAuthUser->oauthCredential);
        });

        if ($tweet->replied) {
            return Action::danger('Tweet has already been replied to.');
        }

        if ($tweet->skipped) {
            return Action::danger('Tweet has been skipped.');
        }

        dispatch(new ReplyToTweetJob($models->first(), $replyAsId));
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Reply As', 'reply_as')
                ->options(function () {
                    return TwitterAuthUser::all()
                        ->pluck('name', 'id');
                })
        ];
    }
}
