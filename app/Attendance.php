<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'activities', 'location', 'remarks', 'status'];
    public function Users () {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }

    public  function scopeSearch ($query, $value) {
    	$val = trim($value);
    	return $query->whereHas('users', function($q) use ($val){
                $q->Where('fname', 'like', '%' . $val . '%');
                $q->orWhere('lname', 'like', '%' . $val . '%');
            })
    	// return $query
            ->orWhere('activities', 'LIKE', "%" . $val . "%")
        	->orWhere('location', 'LIKE', "%" . $val . "%")
        	->orWhere('remarks', 'LIKE', "%" . $val . "%");
    }
}
