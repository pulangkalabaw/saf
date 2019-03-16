<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oic extends Model
{
    protected $table = "oic";
    protected $guarded = [];
    protected $dates = ['created_at','updated_at'];

    public function getCluster() {
        return $this->hasOne('App\Clusters', 'id', 'cluster_id');
    }

    public function getTeam () {
        return $this->hasOne('App\Teams', 'id', 'team_id');
    }

    public function getAgent () {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /*
     * [ Search Module ]
     *
     *
     */
    public  function scopeSearch($query, $value){
        $user = new User();
        $teams = new Teams();
        $clusters = new Clusters();
        $val = trim($value);

        // Search from the oic
        $return_query = $query->where('assign_date', 'LIKE', "%".$val."%");

        // Search for Clusters
        $clusters = $clusters->where('cluster_name', 'LIKE', "%".$val."%")->get();
        if (!empty($clusters)) {
            $return_query = $return_query->orWhereIn('cluster_id', $clusters->pluck('id'));
        }
        // Search for Team
        $teams = $teams->where('team_name', 'LIKE', "%".$val."%")->get();
        if (!empty($teams)) {
            $return_query = $return_query->orWhereIn('team_id', $teams->pluck('id'));
        }
        // Search for Agents
        $agents = $user->orWhere('fname', 'LIKE', "%".$val."%")
        ->orWhere('lname', 'LIKE', "%".$val."%")
        ->get();
        if (!empty($agents)) {
            $return_query = $return_query->orWhereIn('user_id', $agents->pluck('id'));
        }
        // Then try to search to teams
        return $return_query;

    }
}
