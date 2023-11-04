<?php

namespace App\API\Twitter\Contracts;


use Illuminate\Http\RedirectResponse;

interface TwitterApiInterface
{
    public function authorizeUrl(): RedirectResponse;

    public function handleCallback(string $code);

    public function searchTweets(string $keywords, string $excludedKeywords): TweetSearchResponseInterface;

    public function replyTo(int $id, string $reply): TweetReplyResponseInterface;

    public function detailsOf(int $tweetId): TweetDetailsInterface;

    public function stats(string $tweetIds): TweetStatsResponseInterface;

    public function deleteTweet(int $id): void;
}
