<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\admin\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check()) {
            User::where('kode', Auth::user()->kode)->update(['last_seen' => now()]);
        }
        return $next($request);
    }
}
