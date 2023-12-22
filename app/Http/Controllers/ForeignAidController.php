<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForeignAidController extends Controller
{
    public function __invoke()
    {
        return view('foreign-aid', [
            'showHeader' => true,
        ]);
    }
}
