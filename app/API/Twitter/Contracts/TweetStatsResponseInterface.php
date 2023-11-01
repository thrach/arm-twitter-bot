<?php

namespace App\API\Twitter\Contracts;

use Illuminate\Support\Collection;

interface TweetStatsResponseInterface
{
    public function data(): array;

    public function publicMetrics(): Collection;
}
