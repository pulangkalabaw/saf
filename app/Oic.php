<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oic extends Model
{
    protected $table = "oic";
    protected $guarded = [];
    protected $dates = ['created_at','updated_at'];

    public function getCluster () {
        return $this->hasOne('App\Clusters', 'cluster_id', 'cluster_id');
    }

    public function getTeam () {
        return $this->hasOne('App\Teams', 'team_id', 'team_id');
    }

    public function getAgent () {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /*
     * [ Search Module ]
     * [ search: team_name ]
     *
     */
    public  function scopeSearch($query, $value){
        //Fix this
        $user = new User();
        $teams = new Teams();
        $val = trim($value);

        // Search from the application
        $return_query = $query->where('so_no', 'LIKE', "%".$val."%")
		->orWhere('sr_no', 'LIKE', '%'.$val.'%')
		->orWhere('status', 'LIKE', '%'.$val.'%')
        ->orWhere('customer_name', 'LIKE', '%'.$val.'%');

        // Search for Team
        $team = $teams->where('team_name', 'LIKE', "%".$val."%")->first();
        if (!empty($team)) {
            $return_query = $return_query->orWhere('team_id', $team->id);
        }

        // Then try to search to teams
        return $return_query;

    }
}
