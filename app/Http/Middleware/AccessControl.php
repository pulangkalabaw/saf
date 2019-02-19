<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AccessControl
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
            return redirect()->route('login');
        }
        return $next($request);
    }
}
