<?php

namespace App\Http\Controllers;

use App\Models\SearchTerm;
use App\Models\Tweet;
use App\Models\TweetReply;
use App\Models\TwitterAuthUser;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $searchTerms = SearchTerm::count();
        $twitterAccounts = TwitterAuthUser::count();

        // Date 7 days ago from today
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay(); // Includes today and goes 6 days back
        $today = Carbon::now()->endOfDay();
        $tweets = Tweet::whereBetween('created_at', [$sevenDaysAgo, $today])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $tweetReplies = TweetReply::whereBetween('created_at', [$sevenDaysAgo, $today])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $tweetsCountByDay = [];
        $repliesCountByDay = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $sevenDaysAgo->copy()->addDays($i)->toDateString();
            $dayName = $sevenDaysAgo->copy()->addDays($i)->format('m/d/Y');
            $tweetsCountByDay[$dayName] = isset($tweets[$date]) ? $tweets[$date]['count'] : 0;
            $repliesCountByDay[$dayName] = isset($tweetReplies[$date]) ? $tweetReplies[$date]['count'] : 0;
        }

        return view('dashboard.index' , compact(
            'tweetsCount',
            'repliesCount',
            'searchTerms',
            'twitterAccounts',
            'tweetsCountByDay',
            'repliesCountByDay'
        ));
    }
}
