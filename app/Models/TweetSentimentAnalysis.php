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
 * @property int $tweet_id
 *
 * @property object $analysis
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Tweet $tweet
 */
class TweetSentimentAnalysis extends Model
{
    protected $fillable = [
        'tweet_id',
        'analysis'
    ];

    protected $casts = [
        'analysis' => 'object'
    ];

    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class, 'tweet_id');
    }
}
