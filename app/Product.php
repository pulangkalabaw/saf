<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = "products";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];

    public function scopeSort ($query, $request) {

        // If everything is good
        return $query->orderBy('product_name', $request->get('sort_by'));
    }


    public  function scopeSearch ($query, $value) {
        $val = trim($value);
        return $query->where('product_name', 'LIKE', "%".$val."%");
    }
}
