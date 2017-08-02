<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function activityUsers()
    {
        return $this->hasMany('App\ActivityUser');
    }

    public function getUsers()
    {
        $users = collect();
        foreach ($this->activityUsers as $user) {
            $users->push($user);
        }

        return $users;
    }
}
