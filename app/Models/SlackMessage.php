<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 *
 * @property string $ts_id
 * @property string $message
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read  SlackMessageChannel[] $slackMessageChannels
 * @property-read SlackMessageChannel $slackable
 */
class SlackMessage extends Model
{
    protected $fillable = [
        'ts_id',
        'message'
    ];

    public function slackMessageChannels(): HasMany
    {
        return $this->hasMany(SlackMessageChannel::class, 'slack_message_id');
    }

    public function slackable(): MorphOne
    {
        return $this->morphOne(SlackMessageChannel::class, 'slackable');
    }
}
