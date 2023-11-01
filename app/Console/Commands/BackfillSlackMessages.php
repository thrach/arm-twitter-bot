<?php

namespace App\Console\Commands;

use App\API\Slack\Contracts\ListOfMessagesResponseInterface;
use App\API\Slack\Contracts\SlackApiInterface;
use App\Models\SlackMessage;
use App\Models\Tweet;
use Illuminate\Console\Command;

class BackfillSlackMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-slack-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(public readonly SlackApiInterface $slackApi)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = $this->slackApi->listOfMessages();

        $this->handleResponse($response);
    }

    public function handleResponse(ListOfMessagesResponseInterface $response)
    {
        $progress = $this->getOutput()->createProgressBar($response->messages()->count());

        $response
            ->messages()
            ->each(function ($message) use (&$progress) {
                if (SlackMessage::where('ts_id', $message->ts)->exists()) {
                    $progress->advance();
                    return;
                }
                /** @var SlackMessage $slackMessage */
                $slackMessage = SlackMessage::create([
                    'ts_id' => $message->ts,
                    'message' => $message->text
                ]);

                $pattern = '/https:\/\/twitter\.com\/[^\/]+\/status\/(\d+)/';

                if (preg_match($pattern, $message->text, $matches)) {
                    $twitterId = $matches[1];
                    $this->getOutput()->info($twitterId);
                    $tweet = Tweet::where('tweet_id', $twitterId)->first();

                    if ($tweet) {
                        $slackMessage->slackMessageChannels()
                            ->create([
                                'slackable_id' => $tweet->id,
                                'slackable_type' => Tweet::class,
                            ]);
                    }
                }

                $progress->advance();
            });

        $progress->finish();

        if ($response->nextCursor()) {
            $response = $this->slackApi->listOfMessages($response->nextCursor());

            $this->handleResponse($response);
        }
    }
}
