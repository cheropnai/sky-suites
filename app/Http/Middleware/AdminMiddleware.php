<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->is_admin) { // Assuming is_admin is a boolean column in the users table
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
