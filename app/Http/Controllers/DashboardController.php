<?php

namespace App\Http\Controllers;

use App\Teams;
use App\Clusters;
use App\Statuses;
use App\Application;
use App\ApplicationStatus;
use App\Attendance;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Auth;

class DashboardController extends Controller
{
	//

	public function home () {
		return view ('welcome');
	}


	public function dashboard (Request $request) {

		$application_status_model = new ApplicationStatus();
		$application_model = new Application();
		$statuses_model = new Statuses();
		$teams_model = new Teams();
		$clusters_model = new Clusters();
		$attendance_model = new Attendance();
		$user_model = new User();


		// Widget for counting all application submitted
		$_w = $application_model->applicationStatusCounterWidget(Auth::user());

		$_p = $application_model->productChart(Auth::user());

		// Widget for product chart

		// For product bug *ASK PAUL REAL FOR ANY CHANGES ON THIS CODE
		$_p_prod['prod'] = collect($_p)->filter(function($res){
			if($res['count'] != 0){
				return $res['product'];
			}
		})->pluck('product')->toArray();


		$_p_prod['count'] = collect($_p)->filter(function($res){
			if($res['count'] != 0){
				return $res['count'];
			}
		})->pluck('count')->toArray();
		// END For product bug *ASK PAUL REAL FOR ANY CHANGES ON THIS CODE

		//
		$no_of_status_that_used = $statuses_model->get(['id', 'status'])->map(function ($r) use ($application_model){
			$r['total_count'] = $application_model->where('status', $r['id'])->count();
			return $r;
		});

		// Application counter by cluster
		$application_counter_by_cluster = $application_model->with('getClusterName')->groupBy('cluster_id')
		->get(['cluster_id'])->map(function ($r) use ($application_model){
			$r['total_count'] = $application_model->where('cluster_id', $r['cluster_id'])->count();
			return $r;
		})->sortByDesc('total_count');

		// Application counter by cluster
		$application_counter_by_teams = $application_model->with('getTeam')->groupBy('team_id')
		->get(['team_id'])->map(function ($r) use ($application_model){
			$r['total_count'] = $application_model->where('team_id', $r['team_id'])->count();
			return $r;
		})->sortByDesc('total_count');

		// dd($application_counter_by_teams);

		// dd($request->from);

		return view('app.dashboard', [
			'no_of_status_that_used' => $no_of_status_that_used,
			'application_counter_by_cluster' => $application_counter_by_cluster,
			'application_counter_by_teams' => $application_counter_by_teams,
			'heirarchy' => getHeirarchy2($request->from,$request->to),
			'_w_application_status_counter' => $_w,
			'_w_product_chart' => $_p,
			'_w_prod_data' => $_p_prod,

		]);
	}

	// Method for attendance Dashboard

	public function attendanceDashboard(Request $request){
		// date format should be year-month-day e.g. 2019-03-01
		try{

			$datenow = ($request->date !== null) ? Carbon::parse($request->date) : ((!empty($request->exactdate)) ? Carbon::parse($request->exactdate) : now());
			$tempdate = [
				'prev' => ($request->date !== null) ? Carbon::parse($request->date) : ((!empty($request->exactdate)) ? Carbon::parse($request->exactdate) : now()),
				'next' => ($request->date !== null) ? Carbon::parse($request->date) : ((!empty($request->exactdate)) ? Carbon::parse($request->exactdate) : now()),
			];

		}catch (\Exception $e){
			$datenow = now();
			$tempdate = [
				'prev' =>  now(),
				'next' =>  now(),
			];
		}

		$attendance_model = new Attendance();
		if(!empty($request->exactdate)){
			$myattendance['attendance'] = $attendance_model->where('user_id', auth()->user()->id)->whereDate('created_at', '=', $datenow->format('Y-m-d'))->get();
			$myattendance['currmsg'] =  'Data On '.$datenow->format("F d Y");

		}else{
			$myattendance['attendance'] = $attendance_model->where('user_id', auth()->user()->id)->whereMonth('created_at', '=', $datenow->month)->get();
			$myattendance['currmsg'] =  'For this month of '.$datenow->format("F");

		}
		$myattendance['prev'] =  $tempdate['prev']->subMonths(1)->format('Y-m-d');
		$myattendance['curr'] =  $datenow;
		$myattendance['next'] =  $tempdate['next']->addMonths(1)->format('Y-m-d');


		// RETURN VIEW
		return view('app.attendance.dashboard', [
			'heirarchy' => getHeirarchy2(),
			'myattendance' => $myattendance,
		]);
	}

	public function encoderDashboard(Request $request){

		$application_status_model = new ApplicationStatus();
		$application_model = new Application();
		$statuses_model = new Statuses();
		$teams_model = new Teams();
		$clusters_model = new Clusters();
		$attendance_model = new Attendance();
		$user_model = new User();


		// Widget for counting all application submitted
		$_w = $application_model->applicationStatusCounterWidget(Auth::user());

		$_p = $application_model->productChart(Auth::user());

		// Widget for product chart

		// For product bug *ASK PAUL REAL FOR ANY CHANGES ON THIS CODE
		$_p_prod['prod'] = collect($_p)->filter(function($res){
			if($res['count'] != 0){
				return $res['product'];
			}
		})->pluck('product')->toArray();


		$_p_prod['count'] = collect($_p)->filter(function($res){
			if($res['count'] != 0){
				return $res['count'];
			}
		})->pluck('count')->toArray();
		// END For product bug *ASK PAUL REAL FOR ANY CHANGES ON THIS CODE

		//
		$no_of_status_that_used = $statuses_model->get(['id', 'status'])->map(function ($r) use ($application_model){
			$r['total_count'] = $application_model->where('status', $r['id'])->count();
			return $r;
		});

		// Application counter by cluster
		$application_counter_by_cluster = $application_model->with('getClusterName')->groupBy('cluster_id')
		->get(['cluster_id'])->map(function ($r) use ($application_model){
			$r['total_count'] = $application_model->where('cluster_id', $r['cluster_id'])->count();
			return $r;
		})->sortByDesc('total_count');

		// Application counter by cluster
		$application_counter_by_teams = $application_model->with('getTeam')->groupBy('team_id')
		->get(['team_id'])->map(function ($r) use ($application_model){
			$r['total_count'] = $application_model->where('team_id', $r['team_id'])->count();
			return $r;
		})->sortByDesc('total_count');

		// dd($application_counter_by_teams);
		// return Auth::user()->id;
		// return Application::where('encoder_id', Auth::user()->id)->get();
		$count['total_applications'] = Application::count();
		$count['not_encoded'] = Application::where('encoder_id', null)->count();
		$count['total_encoded'] = Application::where('encoder_id', '<>', null)->count();
		$count['your_encoded'] = Application::where('encoder_id', Auth::user()->id)->count();
		return view('app.encoder-dashboard', [
			'no_of_status_that_used' => $no_of_status_that_used,
			'application_counter_by_cluster' => $application_counter_by_cluster,
			'application_counter_by_teams' => $application_counter_by_teams,
			'heirarchy' => getHeirarchy2($request->from,$request->to),
			'_w_application_status_counter' => $_w,
			'_w_product_chart' => $_p,
			'_w_prod_data' => $_p_prod,
			'count' => $count,
		]);
	}
}
