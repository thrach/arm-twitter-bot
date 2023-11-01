<?php

namespace App\API\Twitter\Contracts;

interface TweetReplyResponseInterface
{
    public function data(): object;

    public function replyId(): int;
}
