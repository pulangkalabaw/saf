<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    //
    protected $table = "plans";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    public function scopeSort ($query, $request) {

        // If everything is good
        return $query->orderBy('plan_name', $request->get('sort_by'));
    }


    public  function scopeSearch ($query, $value) {
        $val = trim($value);
        return $query->where('plan_name', 'LIKE', "%".$val."%")
        ->orWhere('product','LIKE', "%".$val."%")
        ->orWhere('msf','LIKE', "%".$val."%");
    }
}
