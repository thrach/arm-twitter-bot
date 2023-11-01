<?php

namespace App\API\Twitter\Concretes\V2\Responses;

use App\API\Twitter\Contracts\TweetStatsResponseInterface;
use Illuminate\Support\Collection;

class TweetStats implements TweetStatsResponseInterface
{
    public function __construct(public readonly object $response)
    {
    }

    public function data(): array
    {
        return $this->response->data;
    }

    public function publicMetrics(): Collection
    {
        return collect($this->response->data)
            ->map(function ($tweet) {
                $publicMetrics = (array) $tweet->public_metrics;
                $publicMetrics['last_synced_at'] = now();
                return [$tweet->id => $publicMetrics];
            });
    }
}
