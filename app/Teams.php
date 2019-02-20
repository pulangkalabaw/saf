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
            $r['team_ids'] = json_decode($r['team_ids']);
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
            $r['team_ids'] = json_decode($r['team_ids']);
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

        // if (!empty($user->search($val)->first())) {
        //     $user_id = $user->search($val)->first()->id;
        //     $agent_code = $user->search($val)->first()->agent_code;
        //     $return_query = $return_query
        //     ->orWhere('teams.tl_id', $user_id)
        //     ->orWhere('teams.agent_code', $agent_code);
        // }

        // Then try to search to teams
        return $return_query;

    }


    /*
     * [ Get the Team Leader Information using tl_id ]
     *
     */
    public function getTeamLeader() {
        return $this->hasOne('App\User', 'id', 'tl_id');
    }

    /*
     * [ Get the Agent Information using agent_code ]
     *
     */
    public function getAgentCode() {
        return $this->hasOne('App\User', 'agent_code', 'agent_code');
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

    public function setEncoderIdsAttribute($value)
    {
        $this->attributes['encoder_ids'] = json_encode($value);
    }

    public function getEncoderIdsAttribute($value)
    {
        return json_decode($value);
    }


}
