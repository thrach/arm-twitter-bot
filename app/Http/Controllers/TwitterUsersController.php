<?php

namespace App\Http\Controllers;

use App\Models\TwitterAuthUser;
use Illuminate\Http\Request;

class TwitterUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $twitterUsers = TwitterAuthUser::paginate(10);

        return view('twitter-users.index', compact('twitterUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TwitterAuthUser $twitterUser)
    {
        return view('twitter-users.show', compact('twitterUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TwitterAuthUser $twitterAuthUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TwitterAuthUser $twitterUser)
    {

        $twitterUser->update([
            'can_reply_with_this_account' => $request->filled('can_reply_with_this_account')
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TwitterAuthUser $twitterAuthUser)
    {
        //
    }
}
