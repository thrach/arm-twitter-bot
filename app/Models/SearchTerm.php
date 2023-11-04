<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\Tags\HasTags;

/**
 * @mixin Builder
 *
 * @property int $id
 *
 * @property string $reply
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Tweet[] $tweets
 * @property-read SearchTermExclusion[] $searchTermExclusions
 * @property-read KeywordReply $keyword
 */
class SearchTerm extends Model
{
    use HasTags;

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class, 'search_term_id');
    }

    public function keyword(): HasOne
    {
        return $this->hasOne(KeywordReply::class, 'search_term_id');
    }
}
