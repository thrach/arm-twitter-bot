<?php

namespace App\API\Slack\Contracts;

use App\Models\Tweet;

interface SlackApiInterface
{
    public function sendMessage(Tweet $tweet): SendMessageResponseInterface;

    public function deleteMessage(string $ts);

    public function listOfMessages(?string $next = null): ListOfMessagesResponseInterface;

    public function replyTo(string $ts, string $message, Tweet $tweet): SendMessageResponseInterface;

    public function reactTo(string $ts, string $emoji = 'white_check_mark'): void;
}
