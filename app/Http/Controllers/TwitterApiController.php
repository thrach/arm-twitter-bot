<?php

namespace App\Http\Controllers;

use App\API\Twitter\Contracts\TwitterApiInterface;
use Illuminate\Http\Request;

class TwitterApiController extends Controller
{
    public function __construct(public readonly TwitterApiInterface $twitterApi)
    {
    }
    public function authorizeUrl()
    {
        return $this->twitterApi->authorizeUrl();
    }

    public function callback(Request $request)
    {
        $this->twitterApi->handleCallback($request->get('code'));

        return redirect()->to('/nova');
    }
}
