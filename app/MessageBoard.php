<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageBoard extends Model
{
    //
    protected $table = "msg_board";
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];    

    public function user() {
        // $users_model = new User();    	
        // return $users_model->where('id',$posted_by)->first();
        return $this->belongsTo('App\User', 'posted_by');

    }


    // public function scopeSort ($query, $request) {
    //     return $query->orderBy('product_name', $request->get('sort_by'));
    // }


    // public  function scopeSearch ($query, $value) {
    //     $val = trim($value);
    //     return $query->where('product_name', 'LIKE', "%".$val."%");
    // }

}
