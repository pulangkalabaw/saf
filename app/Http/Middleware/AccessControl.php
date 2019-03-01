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
	public function handle($request, Closure $next, ...$can_access)
	{
		// Update sesison
		getSessions();

		// Check point!
		if (!in_array(base64_decode(Auth::user()->role), $can_access)) {

			// whether condition is true
			// return the 404 Page
			// instead of return back()
			abort(404);
		}
		return $next($request);
	}
}
