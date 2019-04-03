<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

use App\Application;

use Carbon\Carbon;

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
		// Check if their is an expired application
		$application = new Application();
		$expires_at = $application->where(['expires_at' => Carbon::now()->toDateString(), 'status' => 'new'])->get();

		if($expires_at){
			$data['status'] = 'expired';
			$data['expires_at'] = null;
			$application->where('expires_at', Carbon::now()->toDateString())->update($data);
		}

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
