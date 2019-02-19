<?php

namespace App;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Statuses extends Model
{
    //
    protected $table = "statuses";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    public function scopeSort ($query, $request) {

        // Check first if sort_in (database column) is exists!
        if (!Schema::hasColumn('statuses', $request->get('sort_in'))) return $query;

        // If everything is good
        return $query->orderBy($request->get('sort_in'), $request->get('sort_by'));
    }


    public  function scopeSearch ($query, $value) {
        $val = trim($value);
        return $query->where('status', 'LIKE', "%".$val."%");
    }
}
