<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Tags\Tag;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $search_term_id
 * @property int|null $search_term_exclusion_id
 *
 * @property string $reply
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read KeywordReplyText[] $replies
 * @property-read  SearchTerm $searchTerm
 * @property-read SearchTermExclusion|null $searchTermExclusion
 */
class KeywordReply extends Model
{
    protected $fillable = [
        'search_term_id',
        'search_term_exclusion_id'
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(KeywordReplyText::class, 'keyword_reply_id');
    }

    public function searchTerm(): BelongsTo
    {
        return $this->belongsTo(SearchTerm::class, 'search_term_id');
    }

    public function searchTermExclusion(): BelongsTo
    {
        return $this->belongsTo(SearchTermExclusion::class, 'search_term_exclusion_id');
    }
}
