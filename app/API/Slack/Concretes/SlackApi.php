<?php

namespace App\API\Slack\Concretes;

use App\API\Slack\Concretes\Responses\ListOfMessages;
use App\API\Slack\Concretes\Responses\SendMessage;
use App\API\Slack\Contracts\ListOfMessagesResponseInterface;
use App\API\Slack\Contracts\SendMessageResponseInterface;
use App\API\Slack\Contracts\SlackApiInterface;
use App\Exceptions\Slack\ListOfMessagesException;
use App\Exceptions\Slack\SendMessageException;
use App\Models\Tweet;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use SlackPhp\BlockKit\Elements\Button;
use SlackPhp\BlockKit\Kit;

class SlackApi implements SlackApiInterface
{
    protected string $apiPrefix = 'api';

    protected ClientInterface $client;

    protected array $config = [];
    public function __construct()
    {
        $this->config = config('services.slack');
        $this->client = new Client([
            'base_uri' => $this->config['base_uri'],
            RequestOptions::HEADERS => [
                'Authorization' => "Bearer {$this->config['bot_token']}"
            ]
        ]);
    }

    public function sendMessage(Tweet $tweet): SendMessage
    {
        try {
            $novaLink = url("/nova/resources/tweets/{$tweet->id}");
            $msg = Kit::message()
                ->blocks(
                    Kit::section("New HYSA Tweet!"),
                    Kit::divider(),
                    Kit::section("*{$tweet->tweet}*"),
                    Kit::divider(),
                    Kit::section("*Used Keywords*\n{$tweet->keywordReply->tags->pluck('name')->join(', ')}"),
                    Kit::divider(),
                    Kit::section("*Template Reply*\n{$tweet->reply}"),
                    Kit::actions([
                        Kit::button('view-in-nova', 'View in Nova')
                            ->url($novaLink)
                    ])
                );

            $payload = array_merge([
                'channel' => $this->config['channel_id'],
            ], $msg->toArray());

            $response = $this->client->post("/{$this->apiPrefix}/chat.postMessage", [
                RequestOptions::JSON => $payload,
                RequestOptions::HEADERS => [
                    "Content-type" => 'application/json'
                ]
            ]);

            return new SendMessage(json_decode($response->getBody()->getContents()));
        } catch (BadResponseException $exception) {
            Log::channel('slack-api')
                ->debug('[CLIENT] Failed to fetch SEND MESSAGE', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new SendMessageException();
        } catch (GuzzleException $exception) {
            Log::channel('slack-api')
                ->debug('[SERVER] Failed to fetch SEND MESSAGE', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);

            throw new SendMessageException();
        }
    }

    public function listOfMessages(?string $next = null): ListOfMessagesResponseInterface
    {
        $query = [
            'channel' => $this->config['channel_id']
        ];

        if ($next) {
            $query['cursor'] = $next;
        }
        try {
            $response = $this->client->get("/{$this->apiPrefix}/conversations.history", [
                RequestOptions::QUERY => $query,
                RequestOptions::HEADERS => [
                    "Content-type" => 'application/json'
                ]
            ]);

            return new ListOfMessages(json_decode($response->getBody()->getContents()));
        } catch (BadResponseException $exception) {
            Log::channel('slack-api')
                ->debug('[CLIENT] Failed to fetch MESSAGES LIST', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new ListOfMessagesException();
        } catch (GuzzleException $exception) {
            Log::channel('slack-api')
                ->debug('[SERVER] Failed to fetch MESSAGES LIST', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);

            throw new ListOfMessagesException();
        }
    }

    public function deleteMessage(string $ts)
    {
        $response = $this->client->post('/api/chat.delete', [
            RequestOptions::JSON => [
                'channel' => $this->config['channel_id'],
                'ts' => $ts
            ]
        ]);
    }

    public function replyTo(string $ts, string $message, Tweet $tweet): SendMessageResponseInterface
    {
        try {
            $msg = Kit::message()
                ->blocks(
                    Kit::section("New Reply To Tweet!"),
                    Kit::divider(),
                    Kit::section("*{$message}*"),
                    Kit::actions([
                        Kit::button('view-in-nova', 'View in Nova')
                            ->url($tweet->nova_link)
                    ]),
                );

            $payload = array_merge([
                'channel' => $this->config['channel_id'],
                'thread_ts' => $ts,
            ], $msg->toArray());

            $response = $this->client->post("/{$this->apiPrefix}/chat.postMessage", [
                RequestOptions::JSON => $payload,
                RequestOptions::HEADERS => [
                    "Content-type" => 'application/json'
                ]
            ]);

            return new SendMessage(json_decode($response->getBody()->getContents()));
        } catch (BadResponseException $exception) {
            Log::channel('slack-api')
                ->debug('[CLIENT] Failed to fetch REPLY TO MESSAGE', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new SendMessageException();
        } catch (GuzzleException $exception) {
            Log::channel('slack-api')
                ->debug('[SERVER] Failed to fetch REPLY TO MESSAGE', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);

            throw new SendMessageException();
        }
    }

    public function reactTo(string $ts, string $emoji = 'white_check_mark'): void
    {
        $this->client->post("{$this->apiPrefix}/reactions.add", [
            RequestOptions::JSON => [
                'channel' => $this->config['channel_id'],
                'name' => $emoji,
                'timestamp' => $ts,
            ]
        ]);
    }
}
