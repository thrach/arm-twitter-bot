<?php

namespace App\API\Twitter\Concretes\V2\Responses;

use App\API\Twitter\Contracts\TweetDetailsInterface;
use Illuminate\Support\Collection;

class TweetDetails implements TweetDetailsInterface
{
    public function __construct(private ?object $response)
    {
    }

    public function users(): Collection
    {
        return collect($this->response->includes->users);
    }
}
