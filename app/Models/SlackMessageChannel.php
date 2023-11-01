<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $slack_message_id
 * @property int $slackable_id
 *
 * @property string $slackable_type
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read SlackMessage $slackMessage
 * @property-read Model $slackable
 */
class SlackMessageChannel extends Model
{
    protected $fillable = [
        'slack_message_id',
        'slackable_id',
        'slackable_type'
    ];

    public function slackMessage(): BelongsTo
    {
        return $this->belongsTo(SlackMessage::class, 'slack_message_id');
    }

    public function slackable(): MorphTo
    {
        return $this->morphTo();
    }
}
