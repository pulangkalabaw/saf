<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Validator;
use App\Teams;
use App\Clusters;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	//
	public function login () {
		return view('auth.login');
	}

	public function postLogin (Request $request) {

		$v = Validator::make($request->all(), [
			'email' => 'required|string|email',
			'password' => 'required|string|min:6',
		]);
		if ($v->fails()) return back()->withErrors($v->errors());

		// Authentication
		if (Auth::attempt(['email' => $request->post('email'), 'password' => $request->post('password')])) {

			if (Auth::user()->isActive != 1) {
				return back()->with([
					'notif.style' => 'danger',
					'notif.icon' => 'times-circle',
					'notif.message' => 'Your account has been deactivated!',
				]);
			}
			$teams = new Teams();
			$clusters_model = new Clusters();
			$auth = Auth::user();

			$_data = searchTeamAndCluster($auth);

			// if user is admin then continue to dashboard
			// if (base64_decode($auth->role) != 'administrator'){
			// 	// if user does not have a team or not a cluster leader return back to login page
			// 	if(count($_data['_t']) == 0 || count($_data['_c']) == 0) {
			// 		return back()->with([
			// 			'notif.style' => 'danger',
			// 			'notif.icon' => 'times-circle',
			// 			'notif.message' => 'no teams or cluster leader found',
			// 		]);
			// 	}
			// 	else {
			//
			// 		Session::put('_t', $_data['_t']);
			// 		Session::put('_c', $_data['_c']);
			//
			// 		// Authenticated
			// 		return redirect()->route('app.dashboard');
			// 	}
			// }

			Session::put('_t', $_data['_t']);
			Session::put('_c', $_data['_c']);

			// Authenticated
			return redirect()->route('app.dashboard');

		}
		else {
			// !Authenticated
			return back()->with([
				'notif.style' => 'danger',
				'notif.icon' => 'times-circle',
				'notif.message' => 'Incorrect email or password',
			]);
		}
	}

	public function logout (Request $request) {
		if (Auth::logout()) {
			return redirect()->route('login');
		}
		else {
			return back();
		}
	}

}
