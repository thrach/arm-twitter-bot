<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $oauth_credential_id
 *
 * @property boolean $can_reply_with_this_account
 *
 * @property string $twitter_id
 * @property string $name
 * @property string $username
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read OauthCredential $oauthCredential
 * @property-read TweetReply[] $replies
 */
class TwitterAuthUser extends Model
{
    protected $fillable = [
        'twitter_id',
        'name',
        'username',
        'can_reply_with_this_account'
    ];

    protected $casts = [
        'can_reply_with_this_account' => 'boolean'
    ];

    public function oauthCredential(): BelongsTo
    {
        return $this->belongsTo(OauthCredential::class, 'oauth_credential_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TweetReply::class, 'replied_as_id');
    }

    public function getStatusAttribute(): string
    {
        if ($this->can_reply_with_this_account) {
            return 'success';
        }

        return 'danger';
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'success' => 'Can reply',
            'danger' => 'Cannot reply',
            default => 'Unknown'
        };
    }
}
