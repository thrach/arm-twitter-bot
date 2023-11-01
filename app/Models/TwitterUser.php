<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 *
 * @property string $name
 * @property string $username
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read string $twitter_url
 *
 * @property-read Tweet[] $tweets
 */
class TwitterUser extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'name'
    ];

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class, 'twitter_user_id');
    }

    public function getTwitterUrlAttribute(): string
    {
        return "https://twitter.com/{$this->username}";
    }
}
