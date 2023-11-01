<?php

namespace App\Nova;

use App\Nova\Actions\ReplyToTweet;
use App\Nova\Actions\SearchForAllKeywords;
use App\Nova\Actions\SkipTweet;
use App\Nova\Actions\UnskipTweet;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Tweet extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Tweet>
     */
    public static $model = \App\Models\Tweet::class;

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
        'tweet',
        'reply'
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
                'skipped' => 'danger',
                'replied' => 'success',
                'pending' => 'info',
            ]),

            Number::make('Tweet ID', 'tweet_id')
                ->readonly(),

            Text::make('Tweet Url', function () {
                    return "<a target='_blank' href='{$this->resource->tweet_url}'>@{$this->resource->twitterUser->username}</a>";
                })
                ->asHtml()
                ->readonly(),

            Text::make('Tweet', 'tweet')
                ->sortable()
                ->readonly(),

            Textarea::make('Reply', 'reply')
                ->sortable(),

            Boolean::make('Replied', 'replied')
                ->sortable()
                ->readonly()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->filterable(),

            Boolean::make('Skipped', 'skipped')
                ->sortable()
                ->readonly()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->filterable(),

            BelongsTo::make('Keyword Reply', 'keywordReply', KeywordReply::class),
            BelongsTo::make('Twitter User', 'twitterUser', TwitterUser::class),
            HasMany::make('Tweet Reply', 'tweetReply', TweetReply::class),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            (new ReplyToTweet())->onlyOnDetail(),
            New SkipTweet(),
            new UnskipTweet(),
            ExportAsCsv::make()
                ->withFormat(function (\App\Models\Tweet $model) {
                    $model->load('actions');

                    return [
                        'ID' => $model->id,
                        'Replied As' => $model->tweetReply?->repliedAs->name ?? '',
                        'Replier' => $model->actions->where('name', 'Reply To Tweet')->first()?->user->name ?? '',
                        'Tweet' => $model->tweet,
                        'Keyword Reply' => $model->keywordReply->tags->pluck('name')->join(','),
                        'Reply' => $model->reply,
                        'Replied' => $model->replied ? 'Yes' : 'No',
                        'Like Count' => $model->tweetReply?->like_count,
                        'Retweet Count' => $model->tweetReply?->retweet_count,
                        'Reply Count' => $model->tweetReply?->reply_count,
                        'Quote Count' => $model->tweetReply?->quote_count,
                        'Bookmark Count' => $model->tweetReply?->bookmark_count,
                        'Impression Count' => $model->tweetReply?->impression_count,
                        'Last Synced At' => $model->tweetReply?->last_synced_at,
                    ];
            })->nameable(),
        ];
    }
}
