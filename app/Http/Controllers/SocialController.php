<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function __invoke()
    {
        return view('social', [
            'showHeader' => true,
        ]);
    }
}
