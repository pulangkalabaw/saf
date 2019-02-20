<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    //
    protected $table = "application_status";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    // public function getStatus ($status_id) {
    //     return Statuses::where('id', $status_id)->firstOrFail();
    // }

    public function addedBy ($application_id) {
        $application_status = $this->where('application_id', $application_id)->firstOrFail();
        return User::where('id', $application_status->added_by)->firstOrFail();
    }
}
