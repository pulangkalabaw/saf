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
		// init
		$cluster_ids = [];
		$cluster_model = new Clusters();

		// select team_ids and cluster_id
		$clusters = $cluster_model->get(['team_ids', 'id'])->toArray();

		// Loop thru clusters
		foreach ($clusters as $cluster) {

			// check first if teams_ids
			// not null
			if (!empty($cluster['team_ids'])) {

				// if this team is in the teams_ids in clusters table
				if (in_array($team_id, $cluster['team_ids'])) {

					// save the cluster id to this variable
					$cluster_ids[] = $cluster['id'];
				}
			}
		}

		return $cluster_model->whereIn('id', $cluster_ids)->get()->toArray();

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
		$return_query = $query->where('team_name', 'LIKE', "%".$val."%")
		->orWhere('team_id', 'LIKE', "%".$val."%");

		// Then try to search to teams
		return $return_query;

	}

	/**
	*
	* Get available Teams
	*
	*/
	public function getAvailableTeams() {
		// Get all tl created
		$cl = Clusters::get()->pluck('team_ids');
		$cl_decoded = json_decode($cl);
		if (empty($cl_decoded[0]))  return $this->get();
		return $this->whereNotIn('id', $cl_decoded)->get();
	}

	/**
	* Get your team's agents
	*/
	public function getTeamsAgents ($agent_ids) { // teams.id

		if (!empty($agent_ids[0])) {
			return User::whereIn('id', $agent_ids[0])->get()->toArray();
		}

		return [];
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
