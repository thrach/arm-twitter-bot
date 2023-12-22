<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class HasVerifiedPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Cookie::has('password_step_passed') || Cookie::get('password_step_passed') !== 'true') {
            return redirect()->to('/');
        }
        return $next($request);
    }
}
