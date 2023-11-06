<?php

namespace App\Http\Controllers;

use App\API\Twitter\Concretes\V2\TwitterApi;
use App\API\Twitter\Contracts\TwitterApiInterface;
use App\Jobs\ReplyToTweetJob;
use App\Models\Tweet;
use App\Models\TwitterAuthUser;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweet::with('twitterUser')->orderByDesc('id')->paginate(10);
        return view('tweets.index', compact('tweets'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        $tweet->load('twitterUser', 'tweetReply', 'searchTerm.keyword.replies');
        $authUsers = TwitterAuthUser::where('can_reply_with_this_account', true)->get();

        return view('tweets.show', compact('tweet', 'authUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        //
    }

    public function reply(Tweet $tweet, Request $request)
    {
        $twitterUserId = $request->get('auth_user_id');
        if (is_null($twitterUserId)) {
            $twitterUserId = TwitterAuthUser::inRandomOrder()->first()->id;
        }

        $authUser = TwitterAuthUser::find($twitterUserId);

        app()->bind(TwitterApiInterface::class, function () use ($authUser) {
            return new TwitterApi($authUser->oauthCredential);
        });

        $request->whenFilled('reply_text', function ($input) use ($tweet) {
            $tweet->update([
                'reply' => $input,
            ]);
        });

        dispatch_sync(new ReplyToTweetJob($tweet, $twitterUserId));

        return redirect()->route('tweets.show', $tweet);
    }
}
