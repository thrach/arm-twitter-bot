<?php

namespace App\API\Twitter\Contracts;

use Illuminate\Support\Collection;

interface TweetSearchResponseInterface
{
    public function tweets(): Collection;

    public function meta(): object;
}
