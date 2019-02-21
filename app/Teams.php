<?php

namespace App;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{

	//
	protected $table = "teams";
	protected $guarded = [];
	protected $dates = ['created_at', 'updated_at'];


	public function getCluster ($team_id) {
		$clusters_model = new Clusters();
		$cluster_search = $clusters_model->with('getClusterLeader')->get()->map(function ($r) use ($team_id) {
			// decode the json
			$r['team_ids'] = $r['team_ids'];
			// then search your team id in team ids (array)
			if (in_array($team_id, $r['team_ids'])) return $r;
		});
		$cluster_search  = array_filter($cluster_search->toArray());
		return array_values($cluster_search);
	}

	/*
	* [ Search for Team Cluster ]
	* [ Cluster is a superior of team hehe ]
	* [ Login use only ]
	*
	*/
	public function clusters ($team_id) {
		$clusters_model = new Clusters();
		$cluster_search = $clusters_model->get()->map(function ($r) use ($team_id) {
			// decode the json
			$r['team_ids'] = $r['team_ids'];
			// then search your team id in team ids (array)
			if (in_array($team_id, $r['team_ids'])) return $r;
		});
		return $cluster_search  = array_filter($cluster_search->toArray());
	}


	/*
	* [ Search Module ]
	* [ search: team_name, tl, agent ]
	*
	*/
	public  function scopeSearch($query, $value){
		//
		$user = new User();
		$val = trim($value);

		// Search from the user table first
		// Use this id to search for the cl, tl, encoder, agent_code
		$return_query = $query->where('teams.team_name', 'LIKE', "%".$val."%")
		->orWhere('tl.fname', 'LIKE', "%".$val."%")->orWhere('tl.lname', 'LIKE', "%".$val."%")
		->orWhere('ac.fname', 'LIKE', "%".$val."%")->orWhere('ac.lname', 'LIKE', "%".$val."%")
		->orWhere('ac.agent_code', 'LIKE', "%".$val."%");

		// Then try to search to teams
		return $return_query;

	}


	/*
	* [ Get the Team Leader Information using tl_id ]
	*
	*/
	public function getTeamLeader ($tl_ids) {
		$user = new User();
		// $tl_ids = json_decode($tl_ids);
		return $user->whereIn('id', $tl_ids)->get();
	}

	/**
	* Get all agents
	*/
	public function getAgents ($agent_ids) {
		$user = new User();
		// $agent_ids = json_decode($agent_ids);
		return $user->whereIn('id', $agent_ids)->get();
	}

	/*
	* [ Get all Encoder Information using encoder_ids ]
	*
	*/
	public function getEncoder($id) {
		$user = new User();
		$ids = json_decode($id);
		return $user->whereIn('id', $ids)->get();
	}

	/**
	 * SET AND GET FOR AGENT ID
	 */

	public function setAgentIdsAttribute($value)
	{
		$this->attributes['agent_ids'] = json_encode($value);
	}

	public function getAgentIdsAttribute($value)
	{
		return json_decode($value);
	}


	/**
	 * SET AND GET FOR TEAM LEADER
	 */

	public function setTlIdsAttribute($value)
	{
		$this->attributes['tl_ids'] = json_encode($value);
	}

	public function getTlIdsAttribute($value)
	{
		return json_decode($value);
	}


}
