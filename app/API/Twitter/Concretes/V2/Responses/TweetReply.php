<?php

namespace App\API\Twitter\Concretes\V2\Responses;

use App\API\Twitter\Contracts\TweetReplyResponseInterface;

class TweetReply implements TweetReplyResponseInterface
{
    public function __construct(public readonly ?object $response)
    {
    }

    public function data(): object
    {
        return $this->response->data;
    }

    public function replyId(): int
    {
        return $this->response->data->id;
    }
}
