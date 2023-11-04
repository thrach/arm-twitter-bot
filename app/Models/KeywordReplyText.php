<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $keyword_reply_id
 *
 * @property string $reply
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read KeywordReply $keywordReply
 */
class KeywordReplyText extends Model
{
    protected $fillable = [
        'keyword_reply_id',
        'reply',
    ];

    public function keywordReply(): BelongsTo
    {
        return $this->belongsTo(KeywordReply::class, 'keyword_reply_id');
    }
}
