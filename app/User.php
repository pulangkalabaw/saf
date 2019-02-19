<?php

namespace App;

use Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'isActive', 'agent_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeSort ($query, $request) {

        // Check first if sort_in (database column) is exists!
        if (!Schema::hasColumn('users', $request->get('sort_in'))) return $query;

        // If everything is good
        return $query->orderBy($request->get('sort_in'), $request->get('sort_by'));
    }


    public  function scopeSearch ($query, $value) {
        $val = trim($value);
        return $query->where('fname', 'LIKE', "%".$val."%")
        ->orWhere('lname', 'LIKE', "%".$val."%")
        ->orWhere('role', 'LIKE', "%".base64_encode($val)."%")
        ->orWhere('email', 'LIKE', "%".$val."%");
    }

    /*
     * [ Get the available Cluster Leader ]
     *
     */
    public function getAvailableClusterLeader() {
        return $this->where('role', base64_encode('cl'))->get();
    }


    /*
     * [ Get the available Team Leader ]
     *
     */
    public function getAvailableTeamLeader() {
        return $this->where('role', base64_encode('tl'))->get();
    }


    /*
     * [ Get the available Agent ]
     *
     */
    public function getAvailableAgent() {
        return $this->where('role', base64_encode('agent'))->get();
    }

    /*
     * [ Get the available Encoder ]
     *
     */
    public function getAvailableEncoder() {
        return $this->where('role', base64_encode('encoder'))->get();
    }
}