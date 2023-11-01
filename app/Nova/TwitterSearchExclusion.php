<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TwitterSearchExclusion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\TwitterSearchExclusion>
     */
    public static $model = \App\Models\TwitterSearchExclusion::class;

    public function title()
    {
        return $this->keyword;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'keyword'
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

            Text::make('Keyword', 'keyword')
                ->sortable()
                ->filterable()
                ->rules('required')
                ->required(),

            BelongsToMany::make('Keyword Replies', 'keywordReplies', KeywordReply::class),
        ];
    }
}
