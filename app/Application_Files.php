<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application_Files extends Model
{
    protected $fillable = ['application_id', 'attached_files[]'];
    protected $table = "Application_Files";
    protected $dates = ['created_at', 'updated_at'];

    public function Application(){
        return $this->hasOne('App\Application', 'application_id', 'application_id');
    }
}
