<?php

namespace App;

use Schema;
use Session;
use App\Application;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
	//
	protected $table = "saf_applications";
	protected $guarded = [];
	protected $dates = ['created_at', 'updated_at'];

	/**
	* Product Chart
	*/
	public function productChart ($auth) {
		$_p = [];

		$_p[] = [
			'product' => 'smart',
			'count' => $this->applicationSubmitted($auth, ['col' => 'product', 'value' => 'smart'])->count()
		];
		$_p[] = [
			'product' => 'smart_bro',
			'count' => $this->applicationSubmitted($auth, ['col' => 'product', 'value' => 'smart_bro'])->count()
		];
		$_p[] = [
			'product' => 'sun',
			'count' => $this->applicationSubmitted($auth, ['col' => 'product', 'value' => 'sun'])->count()
		];

		return $_p;
	}

	/**
	* Application Status Counter Widget
	*/
	public function applicationStatusCounterWidget ($auth) {
		$_w = [];

		$_w['application_status_c'][] = [
			'status' => 'new',
			'count' => $this->applicationSubmitted($auth, ['col' => 'status', 'value' => 'new'])->count(),
		];

		$_w['application_status_c'][] = [
			'status' => 'cancelled',
			'count' => $this->applicationSubmitted($auth, ['col' => 'status', 'value' => 'cancelled'])->count(),
		];

		$_w['application_status_c'][] = [
			'status' => 'paid',
			'count' => $this->applicationSubmitted($auth, ['col' => 'status', 'value' => 'paid'])->count(),
		];

		$_w['application_status_c'][] = [
			'status' => 'activated',
			'count' => $this->applicationSubmitted($auth, ['col' => 'status', 'value' => 'activated'])->count(),
		];

		$_w['application_total_c'] = $this->applicationSubmitted($auth)->count();

		return $_w;
	}


	/**
	* Count all application submitted
	* Requirements: User model
	*/
	public function applicationSubmitted ($auth, $status_filter = []) {

		$applications = new Application();

		if (base64_decode($auth->role) == 'user'){

			// Filter application status
			if (!empty($status_filter)) {

				// Check what status they want to filter
				$col = $status_filter['col'];
				$value = $status_filter['value'];
				$applications = $applications->where($col, $value);

				// Agent
				if (in_array('agent', checkPosition($auth, ['agent']))) {
					$applications = $applications->where('agent_id', $auth->id);
				}

				// TL and CL
				else {

					// $applications = $applications->whereIn('team_id', collect(Session::get('_t'))->map(function($r){
					// 	return $r['id'];
					// }))
					// ->orWhereIn('cluster_id', collect(Session::get('_c'))->map(function($r){
					// 	return $r['cluster_id'];
					// }));

					if(!empty(Session::get('_c'))){
						// if you are cl
						$applications = $applications->whereIn('cluster_id', collect(Session::get('_c'))->pluck('cl_ids')->values())->where($col, $value);
					}else{
						$applications = $applications->whereIn('agent_id', collect(collect(Session::get('_t'))->pluck('agent_ids')->values()[0])->map(function($res){
							return (int)$res;
						}))->where($col, $value);
						// dd(collect(collect(Session::get('_t'))->pluck('agent_ids')->values()[0])->map(function($res){
						// 	return (int)$res;
						// }));
					}

					// $applications = $applications->orWhereIn('agent_id', collect(Session::get('_t'))->pluck('agent_ids')->values())
					// ->orWhereIn('cluster_id', collect(Session::get('_c'))->pluck('cl_ids')->values())
					// ->where($col, $value);

				}
			}


		}
		else {
			if(!empty($status_filter)){
				$col = $status_filter['col'];
				$value = $status_filter['value'];
				$applications = $applications->where($col, $value);
			}else{
				$applications = $applications;

			}
		}

		return $applications;
	}

	/*
	* [ Sorting Module ]
	* [ search: team_name ]
	*
	*/
	public function scopeSort ($query, $request) {

		// Check first if sort_in (database column) is exists!
		if (!Schema::hasColumn('saf_applications', $request->get('sort_in'))) return $query;

		// If everything is good
		return $query->orderBy($request->get('sort_in'), $request->get('sort_by'));
	}


	/*
	* [ Search Module ]
	* [ search: team_name ]
	*
	*/
	public function scopeSearch($return_query, $value){
		$plan = new Plans();
		$teams = new Teams();
		$val = trim($value);

		// Search from the application
		$return_query = $return_query->where('so_no', 'LIKE', "%".$val."%")
		->orWhere('sr_no', 'LIKE', '%'.$val.'%')
		->orWhere('status', 'LIKE', '%'.$val.'%')
		->orWhere('customer_name', 'LIKE', '%'.$val.'%')
		->orWhere('product', 'LIKE', '%'.$val.'%');

		// Search for Team
		// Check Team if empty
		$team = $teams->where('team_name', 'LIKE', "%".$val."%")->get();
		if (!empty($team))
		{
			$return_query = $return_query->orWhereIn('team_id', $team->pluck('id'));
		}

		// Search for Plan
		// Check plan if empty
		$plan = $plan->where('plan_name', 'LIKE', "%".$val."%")->get();
		if (!empty($plan))
		{
			$return_query = $return_query->orWhereIn('plan_id', $plan->pluck('id'));
			//dd($return_query->get());
		}

		// Then try to search to teams
		return $return_query;
	}


	/*
	* [ Get the recent status ]
	* [ table: application_status ]
	*
	*/
	public function recentStatusShort ($application_id) {
		$application_status = new ApplicationStatus();
		$application = $application_status->where('application_id', $application_id)->orderBy('id', 'desc')->first();
		if (empty($application)) return "-";
		return $application->status_id;
	}

	public function allStatus ($application_id) {
		$application_status = new ApplicationStatus();
		$application = $application_status->where('application_id', $application_id)->get();
		if (empty($application)) return "-";
		return $application;
		// return $application_status->getStatus($application->status_id)->status;
	}

	public function getInsertBy () {
		return $this->hasOne('App\User', 'id', 'insert_by');
	}

	public function getEncoder ($id) {
		return \App\User::where('id', $id)->first();
	}

	public function getClusterName () {
		return $this->hasOne('App\Clusters', 'cluster_id', 'cluster_id');
	}

	public function getAgentName () {
		return $this->hasOne('App\User', 'id', 'agent_id');
	}

	public function getTeam () {
		return $this->hasOne('App\Teams', 'id', 'team_id');
	}

	public function getDevice () {
		return $this->hasOne('App\Devices', 'device_id', 'device_name');
	}

	public function getPlan () {
		return $this->hasOne('App\Plans', 'id', 'plan_id');
	}

	public function getProduct () {
		return $this->hasOne('App\Product', 'product_id', 'product_type');
	}

}
