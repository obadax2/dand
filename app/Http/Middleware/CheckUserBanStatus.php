<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserBanStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->banned) {
            Auth::logout(); 
            return redirect('/login')->withErrors(['Your account has been banned.']);
        }

        return $next($request);
    }
}