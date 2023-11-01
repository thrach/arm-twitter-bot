<?php

namespace App\Http\Controllers;

use App\Models\KeywordReply;
use App\Models\Tweet;
use App\Models\TweetReply;
use App\Models\TwitterAuthUser;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $tweetsCount = Tweet::count();
        $repliesCount = TweetReply::count();
        $searchTerms = KeywordReply::count();
        $twitterAccounts = TwitterAuthUser::count();
        return view('dashboard' , compact('tweetsCount', 'repliesCount', 'searchTerms', 'twitterAccounts'));
    }
}
