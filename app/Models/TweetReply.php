<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $tweet_id
 * @property int|null $replied_as_id
 * @property int $twitter_post_id
 * @property int $retweet_count
 * @property int $reply_count
 * @property int $like_count
 * @property int $quote_count
 * @property int $bookmark_count
 * @property int $impression_count
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $last_synced_at
 * @property Carbon $tweet_deleted_at
 *
 * @property-read string $status
 *
 * @property-read Tweet $tweet
 * @property-read SlackMessageChannel $slackable
 * @property-read TwitterAuthUser|null $repliedAs
 */
class TweetReply extends Model
{
    protected $fillable = [
        'tweet_id',
        'twitter_post_id',
        'retweet_count',
        'reply_count',
        'like_count',
        'quote_count',
        'bookmark_count',
        'impression_count',
        'last_synced_at',
        'replied_as_id',
        'tweet_deleted_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'tweet_deleted_at' => 'datetime'
    ];

    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class, 'tweet_id');
    }

    public function slackable(): MorphOne
    {
        return $this->morphOne(SlackMessageChannel::class, 'slackable');
    }

    public function repliedAs(): BelongsTo
    {
        return $this->belongsTo(TwitterAuthUser::class, 'replied_as_id');
    }

    public function getStatusAttribute(): string
    {
        return $this->tweet_deleted_at ? 'deleted' : 'active';
    }
}
