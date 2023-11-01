<?php

namespace App\Nova\Actions;

use App\Jobs\SearchForKeywordTweets;
use App\Jobs\SearchForTweets;
use App\Models\KeywordReply;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class SearchForAllKeywords extends Action
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
        $models->each(function (KeywordReply $keywordReply) {
            dispatch(new SearchForKeywordTweets($keywordReply));
        });
    }
}
