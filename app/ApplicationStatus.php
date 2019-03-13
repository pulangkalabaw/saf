<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    //
    protected $table = "application_status";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    public function getStatus ($status_id) {
        return Statuses::where('id', $status_id)->firstOrFail();
    }

    public function addedBy ($application_id) {
        $as = new ApplicationStatus();
        $application_status = $as->where('application_id', $application_id)->first();
        return User::where('id', $application_status->added_by)->first();
    }
    // Shows all the edited application status
    public function editedStatuses ($application_id) {
        return ApplicationStatus::where('application_id',$application_id)->get()->map(function ($response){
			$response['added_by'] = User::where('id',$response['added_by'])->first(['fname','lname']);
			return $response;
		});
    }

    public function appStatus ($app_id) {
        return $this->where('application_id', $app_id)->get()->map(function ($r) {
			$agents = User::get();
			$r['added_by'] = $agents->where('id',$r['added_by'])->first();
			return $r;
		});
    }
}
