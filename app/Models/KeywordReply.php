<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property-read TwitterSearchExclusion[] $twitterSearchExclusions
 */
class KeywordReply extends Model
{
    use HasTags;

    protected $fillable = [
        'reply',
    ];

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class, 'keyword_reply_id');
    }

    public function twitterSearchExclusions(): BelongsToMany
    {
        return $this->belongsToMany(TwitterSearchExclusion::class);
    }
}
