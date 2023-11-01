<?php

namespace App\API\Slack\Contracts;

interface SendMessageResponseInterface
{
    public function message(): string;

    public function ts(): string;
}
