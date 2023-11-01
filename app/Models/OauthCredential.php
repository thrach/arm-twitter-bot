<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 *
 * @property stirng $provider
 * @property string $access_token
 * @property string $refresh_token
 * @property string $scope
 *
 * @property Carbon $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read TwitterAuthUser $twitterAuthUser
 */
class OauthCredential extends Model
{
    const TWITTER_PROVIDER = 'twitter';

    protected $fillable = [
        'provider',
        'access_token',
        'refresh_token',
        'expires_at',
        'scope'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function twitterAuthUser(): HasOne
    {
        return $this->hasOne(TwitterAuthUser::class, 'oauth_credential_id');
    }
}
