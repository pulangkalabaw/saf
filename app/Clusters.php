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
    public function getClusterLeader() {
        return $this->hasOne('App\User', 'id', 'cl_id');
    }

    /*
     * [ Get all Encoder Information using encoder_ids ]
     *
     */
    public function getTeams($team_id, $session = []) {
        $teams = new Teams();
        $ids = json_decode($team_id);
        $teams = $teams->whereIn('team_id', $ids)->get();

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
        $return_query = $query->where('cluster_name', 'LIKE', "%".$val."%");

        if (!empty($user->search($val)->first())) {
            $user_id = $user->search($val)->first()->id;
            $agent_code = $user->search($val)->first()->agent_code;
            $return_query = $return_query
            ->orWhere('cl_id', $user_id);
        }

        // Then try to search to teams
        return $return_query;

    }
}
