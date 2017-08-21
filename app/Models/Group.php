<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $visible = [
        'id',
        'name',
        'user_count'
    ];

    protected $appends = [
        'user_count'
    ];

    public function activityUsers()
    {
        return $this->hasMany('App\ActivityUser');
    }

    /** 
     * Returns a collection of users belonging to the group, via the
     * activityUsers relationship (pivot table).
     *
     */
    public function getUsers()
    {
        return $this->activityUsers;
    }

    public function getUserCountAttribute()
    {
        return $this->activityUsers->count();
    }
}
