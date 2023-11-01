<?php

namespace App\Jobs;

use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Models\SlackMessage;
use App\Models\Tweet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReplyToTweetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Tweet $tweet, public ?int $replyAsId = null)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(TwitterApiInterface $twitterApi, SlackApiInterface $slackApi): void
    {
        $response = $twitterApi->replyTo($this->tweet->tweet_id, $this->tweet->reply);

        $this->tweet
            ->tweetReply()
            ->create([
                'twitter_post_id' => $response->replyId(),
                'replied_as_id' => $this->replyAsId
            ]);

        $this->tweet->markAsReplied();

        $parentSlackMessage = $this->tweet->slackable->slackMessage;

        $slackResponse = $slackApi->replyTo($parentSlackMessage->ts_id, $this->tweet->reply, $this->tweet);

        /** @var SlackMessage $slackMessage */
        $slackMessage = SlackMessage::create([
            'ts_id' => $slackResponse->ts(),
            'message' => $slackResponse->message()
        ]);

        $slackMessage->slackMessageChannels()
            ->create([
                'slackable_id' => $this->tweet->id,
                'slackable_type' => Tweet::class,
            ]);

        $slackMessage->slackMessageChannels()
            ->create([
                'slackable_id' => $slackMessage->id,
                'slackable_type' => SlackMessage::class,
            ]);

        $slackApi->reactTo($parentSlackMessage->ts_id);
    }
}
