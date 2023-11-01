<?php

namespace App\API\Twitter\Contracts;

use Illuminate\Support\Collection;

interface TweetDetailsInterface
{
    public function users(): Collection;
}
