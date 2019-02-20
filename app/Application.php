<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Application extends Model
{
    //
    protected $table = "applications";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
     * [ Search Module ]
     * [ search: team_name ]
     *
     */
    public  function scopeSearch($query, $value){
        // 
        $user = new User();
        $teams = new Teams();
        $val = trim($value);
        // Search from the user table first
        // Use this id to search for the cl, tl, encoder, agent_code
        $return_query = $query->where('application_id', 'LIKE', "%".$val."%")
        // ->orWhere('plan_applied', 'LIKE', '%'.$val.'%')
        ->orWhere('sr_no', 'LIKE', '%'.$val.'%')
        ->orWhere('customer_name', 'LIKE', '%'.$val.'%');
        // Search for Encoder
        $user = $user->search($val)->first();
        if (!empty($user)) {
            $return_query = $return_query->orWhere('user_id', $user->id);
        }
        // Search for Team
        $team = $teams->where('team_name', 'LIKE', "%".$val."%")->first();
        if (!empty($team)) {
            $return_query = $return_query->orWhere('team_id', $team->team_id);
        }
        // Then try to search to teams
        return $return_query;

    }
    /*
     * [ Get the recent status ]
     * [ table: application_status ]
     *
     */
    public function recentStatusShort ($application_id, $col) {
        $application_status = new ApplicationStatus();
        $application = $application_status->where('application_id', $application_id)->orderBy('id', 'desc')->first();
        if (empty($application)) return "-";
        if ($col == "id") {
            return $application_status->getStatus($application->status_id)->id;
        }
        else {
            return $application_status->getStatus($application->status_id)->status;
        }
    }
    public function allStatus ($application_id) {
        $application_status = new ApplicationStatus();
        $application = $application_status->where('application_id', $application_id)->get();
        if (empty($application)) return "-";
        return $application;
        // return $application_status->getStatus($application->status_id)->status;
    }
    public function getEncoder () {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function getClusterName () {
        return $this->hasOne('App\Clusters', 'cluster_id', 'cluster_id');
    }
  
    public function getAgentName () {

    }
    public function getTeam () {
        return $this->hasOne('App\Teams', 'team_id', 'team_id');
    }
    public function getDevice () {
        return $this->hasOne('App\Devices', 'device_id', 'device_name');
    }
    public function getPlan () {
        return $this->hasOne('App\Plans', 'plan_id', 'plan_applied');
    }
    public function getProduct () {
        return $this->hasOne('App\Product', 'product_id', 'product_type');
    }
}
