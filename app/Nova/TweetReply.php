<?php

namespace App\Nova;

use App\Nova\Actions\DeleteTweetFromTwitter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class TweetReply extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\TweetReply>
     */
    public static $model = \App\Models\TweetReply::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Badge::make('Status', function () {
                return $this->resource->status;
            })->map([
                'deleted' => 'danger',
                'active' => 'success',
            ]),

            Number::make('Tweet Id', 'twitter_post_id')->readonly(),

            Number::make('Retweet Count', 'retweet_count')->readonly(),

            Number::make('Reply Count', 'reply_count')->readonly(),

            Number::make('Like Count', 'like_count')->readonly(),

            Number::make('Quote Count', 'quote_count')->readonly(),

            Number::make('Bookmark Count', 'bookmark_count')->readonly(),

            Number::make('Impression Count', 'impression_count')->readonly(),

            DateTime::make("Last Synced At", 'last_synced_at')->readonly(),

            BelongsTo::make('Tweet', 'tweet', Tweet::class),

            BelongsTo::make('Replied As', 'repliedAs', TwitterAuthUser::class)
                ->nullable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new DeleteTweetFromTwitter(),
        ];
    }
}
