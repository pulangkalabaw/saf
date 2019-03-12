<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance_image extends Model
{
    protected $fillable = ['user_id', 'image', 'alt', 'status', 'has_date'];

    public function User() {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
}
