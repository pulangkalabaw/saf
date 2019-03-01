<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clusters extends Model
{
    //
    protected $table = "clusters";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
     * [ Search for Team Cluster ]
     * [ Cluster is a superior of team hehe ]
     *
     */
    public function teams ($team_id) { // team_id (array)
        $teams_model = new Teams();
        return $teams_model->whereIn('team_id', $team_id)->get()->map(function ($r) {
            return $r['team_id'];
        })->toArray();
    }

    /*
     * [ Get the Team Leader Information using tl_id ]
     *
     */
    public function getClusterLeader($cl_ids) {
		$user = new User();
		// $tl_ids = json_decode($tl_ids);
		return $user->whereIn('id', $cl_ids)->get();
    }

    /*
     * [ Get all Encoder Information using encoder_ids ]
     *
     */
    public function getTeams($team_id, $session = []) {
        $teams = new Teams();
        $ids = $team_id;
        $teams = $teams->whereIn('id', $ids)->get();

        if (count($session) != 0) {
            $teams = $teams->map(function($r) use ($session){
                if (in_array($r->team_id, $session)) {
                    return $r;
                }
            });

            $teams = array_filter($teams->toArray());
        }
        return $teams;
    }

    /*
     * [ Search Module ]
     * [ search: cluster_name ]
     *
     */
    public  function scopeSearch($query, $value){
        //
        $user = new User();
        $val = trim($value);

        // Search from the user table first
        // Use this id to search for the cl, tl, encoder, agent_code
        $return_query = $query->where('cluster_id', 'LIKE', "%".$val."%")
		->orWhere('cluster_name', 'LIKE', "%".$val."%");

        // Then try to search to teams
        return $return_query;

    }

    public function setTeamIdsAttribute($value)
    {
        $this->attributes['team_ids'] = json_encode($value);
    }

    public function getTeamIdsAttribute($value)
    {
        return json_decode($value);
    }


	public function setClIdsAttribute($value)
    {
        $this->attributes['cl_ids'] = json_encode($value);
    }

    public function getClIdsAttribute($value)
    {
        return json_decode($value);
    }
}
