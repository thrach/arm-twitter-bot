<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Laravel\Nova\Actions\Actionable;
use Spatie\Tags\HasTags;

/**
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
    use HasTags,
        Actionable;
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
