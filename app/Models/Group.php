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
     * Returns count of relationships to pivot table this group has,
     * i.e. how many rows in activity_user have group_id of $this->id.
     */
    public function getUserCountAttribute()
    {
        return $this->activityUsers->count();
    }
}
