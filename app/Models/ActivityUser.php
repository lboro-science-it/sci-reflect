<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityUser extends Model
{
    /** 
     * Model for the pivot table activity_user
     */
    protected $table = 'activity_user';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
