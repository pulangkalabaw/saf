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
    public function handle($request, Closure $next,...$can_access)
    {
        if (!in_array(base64_decode(Auth::user()->role), $can_access)) {
            return back();
        }
        return $next($request);
    }
}
