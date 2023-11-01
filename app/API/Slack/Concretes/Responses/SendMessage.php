<?php

namespace App\API\Slack\Concretes\Responses;

use App\API\Slack\Contracts\SendMessageResponseInterface;

class SendMessage implements SendMessageResponseInterface
{
    public function __construct(public readonly ?object $response)
    {
    }

    public function message(): string
    {
        return $this->response->message->text;
    }

    public function ts(): string
    {
        return $this->response->ts;
    }
}
