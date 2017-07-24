<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function skills()
    {
        return $this->hasMany('App\Skill');
    }
}
