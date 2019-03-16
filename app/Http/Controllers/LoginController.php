<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Validator;

use App\Teams;
use App\Clusters;
use App\Oic;

use Carbon\Carbon;
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

			// delete OIC from OIC table if date is today
			$oic = new Oic();
			$oic->where('expired_at', Carbon::now()->toDateString())->delete();

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

			$_data = getMyClusterAndTeam($auth);

			Session::put('_t', $_data['_t']);
			Session::put('_c', $_data['_c']);
			Session::put('_a', $_data['_a']);

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
		Auth::logout();
		return redirect()->route('login');
	}

}
