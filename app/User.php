<?php

namespace App;

use Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name', 'email', 'password', 'role', 'isActive', 'target'
	];

	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
		'password', 'remember_token',
	];

	public function scopeSort ($query, $request) {

		// Check first if sort_in (database column) is exists!
		if (!Schema::hasColumn('users', $request->get('sort_in'))) return $query;

		// If everything is good
		return $query->orderBy($request->get('sort_in'), $request->get('sort_by'));
	}


	public  function scopeSearch ($query, $value) {
		$val = trim($value);
		return $query->where('fname', 'LIKE', "%".$val."%")
		->orWhere('lname', 'LIKE', "%".$val."%")
		->orWhere('role', 'LIKE', "%".base64_encode($val)."%")
		->orWhere('email', 'LIKE', "%".$val."%");
	}

	public function exceptEncoder() {
		return $this->where('role', '!=', base64_encode('encoder'));
	}

	/*
	* [ Get the available Cluster Leader ]
	*
	*/
	public function getAvailableClusterLeader($cl_ids = []) {
		// return $this->get();

		$clusters_m = new Clusters();
		$return = [];


		$tl = $clusters_m->get()->pluck('cl_ids');
		$cl_decoded = json_decode($tl);

		if (empty($cl_decoded[0]))  $return = $this->exceptEncoder()->get()->toArray();
		$return = $this->exceptEncoder()->whereNotIn('id', $cl_decoded)->get()->toArray();

		if (count($cl_ids) != 0) {
			// add the current team(s) when returning
			$current_cl = $this->exceptEncoder()->whereIn('id', $cl_ids)->get()->toArray();
			$return = array_unique(array_merge($return, $current_cl), SORT_REGULAR);
		}

		return $return;

	}


	/*
	* [ Get the available Team Leader ]
	*
	*/
	public function getAvailableTeamLeader($tl_ids = []) {
		$teams_m = new Teams();
		$return = [];
		//
		// Get all tl created
		$tl = $teams_m->get()->pluck('tl_ids');

		// decode and filter
		$tl_decoded = collect(json_decode($tl))->filter();

		// dd(collect($tl_decoded)->filter());
		if (empty($tl_decoded[0]))  $return = $this->exceptEncoder()->get()->toArray();
		$return = $this->exceptEncoder()->whereNotIn('id', $tl_decoded)->get()->toArray();

		if (count($tl_ids) != 0) {
			// add the current team(s) when returning
			$current_tl = $this->exceptEncoder()->whereIn('id', $tl_ids)->get()->toArray();
			$return = array_unique(array_merge($return, $current_tl), SORT_REGULAR);
		}

		return $return;
	}


	/*
	* [ Get the available Agent ]
	*
	*/
	public function getAvailableAgent($ag_ids = []) {
		$teams_m = new Teams();
		$return = [];
		//
		$agent = $teams_m->get()->pluck('agent_ids'); // not really a agent code, it is a user.id
		$agent_decoded = json_decode($agent);
		if (empty($agent_decoded[0]))  $return = $this->exceptEncoder()->get()->toArray();
		$return = $this->exceptEncoder()->whereNotIn('id', $agent_decoded)->get()->toArray();

		if (count($ag_ids) != 0) {
			// add the current team(s) when returning
			$current_tl = $this->exceptEncoder()->whereIn('id', $ag_ids)->get()->toArray();
			$return = array_unique(array_merge($return, $current_tl), SORT_REGULAR);
		}

		return $return;
	}

	/*
	* [ Get the available Encoder ]
	*
	*/
	public function getAvailableEncoder() {
		return $this->where('role', base64_encode('encoder'))->get();
	}
}
