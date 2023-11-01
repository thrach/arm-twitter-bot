<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 *
 * @property string $keyword
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read KeywordReply[] $keywordReplies
 */
class TwitterSearchExclusion extends Model
{
    protected $fillable = [
        'keyword'
    ];

    public function keywordReplies(): BelongsToMany
    {
        return $this->belongsToMany(KeywordReply::class);
    }
}
