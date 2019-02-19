<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    //
    protected $table = "devices";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    public function scopeSort ($query, $request) {

        // If everything is good
        return $query->orderBy('devices_name', $request->get('sort_by'));
    }


    public  function scopeSearch ($query, $value) {
        $val = trim($value);
        return $query->where('devices_name', 'LIKE', "%".$val."%");
    }
}
