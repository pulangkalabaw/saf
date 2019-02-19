<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
          if (!in_array(base64_decode(Auth::user()->role), ['administrator'])) {
            return back();
        }
        return $next($request);
    }
}
