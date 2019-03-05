<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'activities', 'location', 'remarks', 'status'];
    public function Users () {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
}
