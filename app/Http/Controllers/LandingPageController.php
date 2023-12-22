<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        if (Cookie::has('password_step_passed') && Cookie::get('password_step_passed') === 'true') {
            return redirect()->to('/social');
        }

        return view('landing', [
            'showHeader' => false,
        ]);
    }
}
