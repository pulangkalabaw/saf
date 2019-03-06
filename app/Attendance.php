<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['cluster_id', 'team_id', 'user_id', 'activities', 'location', 'remarks', 'status', 'created_by', 'modified_by', 'modified_remarks'];
    public function Users () {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
}
