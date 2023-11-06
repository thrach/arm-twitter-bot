<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\Tags\HasTags;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $keyword_reply_id
 * @property int $twitter_user_id
 * @property int $tweet_id
 *
 * @property boolean $replied
 * @property boolean $skipped
 *
 * @property string $reply
 * @property string $tweet
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read string $tweet_url
 * @property-read string $nova_link
 *
 * @property-read string $status
 *
 * @property-read SearchTerm $searchTerm
 * @property-read TwitterUser $twitterUser
 * @property-read TweetReply $tweetReply
 * @property-read SlackMessageChannel $slackable
 */
class Tweet extends Model
{
    protected $fillable = [
        'keyword_reply_id',
        'twitter_user_id',
        'tweet_id',
        'replied',
        'skipped',
        'reply',
        'tweet',
    ];

    protected $casts = [
        'replied' => 'boolean',
        'skipped' => 'boolean'
    ];

    public function searchTerm(): BelongsTo
    {
        return $this->belongsTo(SearchTerm::class, 'search_term_id');
    }

    public function twitterUser(): BelongsTo
    {
        return $this->belongsTo(TwitterUser::class, 'twitter_user_id');
    }

    public function tweetReply(): HasOne
    {
        return $this->hasOne(TweetReply::class, 'tweet_id');
    }

    public function sentiment(): HasOne
    {
        return $this->hasOne(TweetSentimentAnalysis::class, 'tweet_id');
    }

    public function getTweetUrlAttribute(): string
    {
        return "{$this->twitterUser->twitter_url}/status/{$this->tweet_id}/";
    }

    public function getStatusAttribute(): string
    {
        if ($this->replied) {
            return 'success';
        }

        if ($this->skipped) {
            return 'danger';
        }

        return 'info';
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'success' => 'Replied',
            'danger' => 'Skipped',
            default => 'Pending',
        };
    }

    public function markAsReplied(): void
    {
        $this->replied = true;
        $this->save();
    }
}
