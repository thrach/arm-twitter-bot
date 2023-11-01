<?php

namespace App\API\Twitter\Concretes\V2\Responses;

use App\API\Twitter\Contracts\TweetSearchResponseInterface;
use Illuminate\Support\Collection;

class TweetSearch implements TweetSearchResponseInterface
{
    public function __construct(public readonly object $response)
    {
    }

    public function tweets(): Collection
    {
        return collect($this->response->data);
    }

    public function meta(): object
    {
        return $this->response->meta;
    }
}
