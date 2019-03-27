<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application_Files extends Model
{
    protected $fillable = ['file[]'];
}
