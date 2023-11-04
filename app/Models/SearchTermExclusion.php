<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

/**
 * @mixin Builder
 *
 * @property int $id
 *
 * @property string $keyword
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read SearchTerm[] $keywordReplies
 * @property-read Tag[] $tags
 */
class SearchTermExclusion extends Model
{
    use HasTags;

    protected $fillable = [
        'keyword'
    ];

    public function keywordReplies(): BelongsToMany
    {
        return $this->belongsToMany(SearchTerm::class);
    }
}
