<?php

namespace App\Models;

use App\API\Slack\Contracts\SlackApiInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Laravel\Nova\Actions\Actionable;

/**
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
 * @property-read KeywordReply $keywordReply
 * @property-read TwitterUser $twitterUser
 * @property-read TweetReply $tweetReply
 * @property-read SlackMessageChannel $slackable
 */
class Tweet extends Model
{
    use Actionable;
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

    public static function booted()
    {
        self::created(function (Tweet $tweet) {
            $response = resolve(SlackApiInterface::class)->sendMessage($tweet);

            /** @var SlackMessage $slackMessage */
            $slackMessage = SlackMessage::create([
                'ts_id' => $response->ts(),
                'message' => $response->message()
            ]);

            $slackMessage->slackMessageChannels()
                ->create([
                    'slackable_id' => $tweet->id,
                    'slackable_type' => Tweet::class
                ]);
        });
    }

    public function keywordReply(): BelongsTo
    {
        return $this->belongsTo(KeywordReply::class, 'keyword_reply_id');
    }

    public function twitterUser(): BelongsTo
    {
        return $this->belongsTo(TwitterUser::class, 'twitter_user_id');
    }

    public function tweetReply(): HasOne
    {
        return $this->hasOne(TweetReply::class, 'tweet_id');
    }

    public function slackable()
    {
        return $this->morphOne(SlackMessageChannel::class, 'slackable');
    }

    public function getTweetUrlAttribute(): string
    {
        return "{$this->twitterUser->twitter_url}/status/{$this->tweet_id}/";
    }

    public function getNovaLinkAttribute(): string
    {
        return url("/nova/resources/tweets/{$this->id}");
    }

    public function getStatusAttribute(): string
    {
        if ($this->replied) {
            return 'replied';
        }

        if ($this->skipped) {
            return 'skipped';
        }

        return 'pending';
    }

    public function markAsReplied(): void
    {
        $this->replied = true;
        $this->save();
    }
}
