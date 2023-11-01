<?php

namespace App\API\Slack\Concretes\Responses;

use App\API\Slack\Contracts\ListOfMessagesResponseInterface;
use Illuminate\Support\Collection;

class ListOfMessages implements ListOfMessagesResponseInterface
{
    public function __construct(public readonly ?object $response)
    {
    }

    public function messages(): Collection
    {
        return collect($this->response->messages);
    }

    public function nextCursor(): ?string
    {
        return $this->response->response_metadata->next_cursor ?? null;
    }
}
