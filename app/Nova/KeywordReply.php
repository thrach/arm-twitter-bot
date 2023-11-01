<?php

namespace App\Nova;

use App\Nova\Actions\SearchForAllKeywords;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\TagsField\Tags;

class KeywordReply extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\KeywordReply>
     */
    public static $model = \App\Models\KeywordReply::class;

    public function title()
    {
        return $this->resource->tags->pluck('name')->join(',');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'keyword',
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

            Tags::make('Keywords', 'keywords')->sortable(),

            Textarea::make('Reply', 'reply')
                ->sortable()
                ->rules(['required']),

            BelongsToMany::make('Twitter Search Exclusions', 'twitterSearchExclusions', TwitterSearchExclusion::class),
        ];
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
            new SearchForAllKeywords(),
        ];
    }
}
