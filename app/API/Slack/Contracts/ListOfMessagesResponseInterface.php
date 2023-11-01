<?php

namespace App\API\Slack\Contracts;

use Illuminate\Support\Collection;

interface ListOfMessagesResponseInterface
{
    public function messages(): Collection;

    public function nextCursor(): ?string;
}
