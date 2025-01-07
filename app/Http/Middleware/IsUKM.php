<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsUKM
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'ukm') {
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have UKM access.');
    }
}
