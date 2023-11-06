<?php

namespace App\API\Twitter\Concretes\V2;

use App\API\Twitter\Concretes\V2\Responses\TweetDetails;
use App\API\Twitter\Concretes\V2\Responses\TweetReply;
use App\API\Twitter\Concretes\V2\Responses\TweetSearch;
use App\API\Twitter\Concretes\V2\Responses\TweetStats;
use App\API\Twitter\Contracts\TweetDetailsInterface;
use App\API\Twitter\Contracts\TweetReplyResponseInterface;
use App\API\Twitter\Contracts\TweetSearchResponseInterface;
use App\API\Twitter\Contracts\TweetStatsResponseInterface;
use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Exceptions\Twitter\AccessTokenFetchException;
use App\Exceptions\Twitter\DeleteTweetException;
use App\Exceptions\Twitter\RefreshTokenFetchException;
use App\Exceptions\Twitter\ReplyToTweetException;
use App\Exceptions\Twitter\SearchTweetsException;
use App\Exceptions\Twitter\TweetDetailsException;
use App\Exceptions\Twitter\TweetStatsException;
use App\Exceptions\Twitter\TwitterMeException;
use App\Models\OauthCredential;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class TwitterApi implements TwitterApiInterface
{
    protected int $version = 2;

    protected array $config = [];

    protected ClientInterface $client;

    public function __construct(public readonly ?OauthCredential $authCredential = null)
    {
        $this->config = config('services.twitter');
        $defaultConfig = [
            'base_uri' => $this->config['base_uri'],
        ];
        /** @var OauthCredential $oauthCredential */
        $oauthCredential = $this->authCredential->exists ? $this->authCredential : OauthCredential::where('provider', OauthCredential::TWITTER_PROVIDER)->first();

        if ($oauthCredential) {
            if ($oauthCredential->expires_at->lt(now()->addSeconds(10))) {
                $this->refreshToken($oauthCredential);
                $oauthCredential->refresh();
            }

            $defaultConfig[RequestOptions::HEADERS] = [
                "Authorization" => "Bearer {$oauthCredential->access_token}"
            ];
        }

        $this->client = new Client($defaultConfig);
    }

    public function authorizeUrl(): RedirectResponse
    {
        $query = http_build_query([
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'response_type' => 'code',
            'code_challenge' => 'challenge',
            'scope' => 'offline.access tweet.read tweet.write users.read',
            'code_challenge_method' => 'plain',
            'state' => 'state'
        ]);

        return redirect()->away("{$this->config['authorize_url']}?$query");
    }

    /**
     * @throws AccessTokenFetchException
     */
    public function handleCallback(string $code): void
    {
        $client = new Client();

        try {
            $response = $client->post($this->config['token_url'], [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    "Authorization" => "Basic " . base64_encode("{$this->config['client_id']}:{$this->config['client_secret']}")
                ],
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->config['client_id'],
                    'code' => $code,
                    'redirect_uri' => $this->config['redirect_uri'],
                    'scope' => 'offline.access tweet.read tweet.write users.read',
                    'code_verifier' => 'challenge',
                ]
            ]);

            $response = json_decode($response->getBody()->getContents());
            $oauthCredential = OauthCredential::create([
                'provider' => OauthCredential::TWITTER_PROVIDER,
                'access_token' => $response->access_token,
                'refresh_token' => $response->refresh_token,
                'expires_at' => now()->addSeconds($response->expires_in),
                'scope' => $response->scope,
            ]);

            $this->getDetailsOfUserFor($oauthCredential);
        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to fetch ACCESS TOKEN', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new AccessTokenFetchException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to fetch ACCESS TOKEN', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);
            throw new AccessTokenFetchException();
        }
    }

    /**
     * @throws TwitterMeException
     */
    public function getDetailsOfUserFor(OauthCredential $oauthCredential)
    {
        try {
            $response = $this->client->get("{$this->version}/users/me", [
                RequestOptions::HEADERS => [
                    "Authorization" => "Bearer {$oauthCredential->access_token}"
                ]
            ]);

            $data = json_decode($response->getBody()->getContents())->data;

            $oauthCredential
                ->twitterAuthUser()
                ->create([
                    'twitter_id' => $data->id,
                    'name' => $data->name,
                    'username' => $data->username,
                ]);

        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to fetch USER DETAILS', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new TwitterMeException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to fetch USER DETAILS', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);

            throw new TwitterMeException();
        }
    }

    /**
     * @throws RefreshTokenFetchException
     */
    public function refreshToken(OauthCredential $oauthCredential): void
    {
        $client = new Client();

        try {
            $response = $client->post($this->config['token_url'], [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    "Authorization" => "Basic " . base64_encode("{$this->config['client_id']}:{$this->config['client_secret']}")
                ],
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'refresh_token',
                    'client_id' => urlencode($this->config['client_id']),
                    'refresh_token' => $oauthCredential->refresh_token
                ]
            ]);

            $response = json_decode($response->getBody()->getContents());

            $oauthCredential->update([
                'access_token' => $response->access_token,
                'refresh_token' => $response->refresh_token,
                'expires_at' => now()->addSeconds($response->expires_in),
            ]);
        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to fetch REFRESH TOKEN', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new RefreshTokenFetchException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to fetch REFRESH TOKEN', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);
            throw new RefreshTokenFetchException();
        }
    }

    /**
     * @throws SearchTweetsException
     */
    public function searchTweets(string $keywords, ?string $excludedKeywords = null): TweetSearchResponseInterface
    {
        $query = "{$keywords} -is:retweet -from:join_juno"; // -is:retweet is to exclude retweets -from:join_juno is to exclude our own tweets

        if ($excludedKeywords) {
            $query .= " {$excludedKeywords}";
        }
        try {
            $response = $this->client->get("{$this->version}/tweets/search/recent", [
                RequestOptions::QUERY => [
                    'query' => $query,
                    'tweet.fields' => 'entities,context_annotations,possibly_sensitive,public_metrics',
                ]
            ]);

            return new TweetSearch(json_decode($response->getBody()->getContents()));
        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to fetch TWEETS SEARCH', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new SearchTweetsException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to fetch TWEETS SEARCH', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);
            throw new SearchTweetsException();
        }
    }

    /**
     * @throws ReplyToTweetException
     */
    public function replyTo(int $id, string $reply): TweetReplyResponseInterface
    {
        try {
            $response = $this->client->post("{$this->version}/tweets", [
                RequestOptions::JSON => [
                    'reply' => [
                        'in_reply_to_tweet_id' => "{$id}",
                    ],
                    'text' => $reply
                ]
            ]);

            return new TweetReply(json_decode($response->getBody()->getContents()));
        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to TWEETS REPLY', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new ReplyToTweetException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to TWEETS REPLY', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);
            throw new ReplyToTweetException();
        }
    }

    /**
     * @throws TweetDetailsException
     */
    public function detailsOf(int $tweetId): TweetDetailsInterface
    {
        try {
            $response = $this->client->get("{$this->version}/tweets/{$tweetId}", [
                RequestOptions::QUERY => [
                    'expansions' => 'attachments.media_keys,referenced_tweets.id,author_id'
                ]
            ]);
            return new TweetDetails(json_decode($response->getBody()->getContents()));
        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to TWEETS DETAILS', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new TweetDetailsException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to TWEETS DETAILS', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);
            throw new TweetDetailsException();
        }
    }

//    public function createWebhook()
//    {
//        $this->client->post("")
//    }
    public function stats(string $tweetIds): TweetStatsResponseInterface
    {
        try {
            $response = $this->client->get("{$this->version}/tweets", [
                RequestOptions::QUERY => [
                    'ids' => $tweetIds,
                    'tweet.fields' => 'public_metrics,non_public_metrics'
                ]
            ]);

            return new TweetStats(json_decode($response->getBody()->getContents()));
        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to TWEETS DETAILS', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new TweetStatsException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to TWEETS DETAILS', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);
            throw new TweetStatsException();
        }
    }

    public function deleteTweet(int $id): void
    {
        try {
            $response = $this->client->delete("{$this->version}/tweets/{$id}");

        } catch (BadResponseException $exception) {
            Log::channel('twitter')
                ->debug('[CLIENT] Failed to DELETE TWEET', [
                    'code' => $exception->getCode(),
                    'response' => $exception->getResponse()->getBody()->getContents(),
                    'message' => $exception->getMessage(),
                ]);

            throw new DeleteTweetException();
        } catch (GuzzleException $exception) {
            Log::channel('twitter')
                ->debug('[SERVER] Failed to DELETE TWEET', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]);
            throw new DeleteTweetException();
        }
    }
}
