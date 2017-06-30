<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public function indicators()
    {
        return $this->hasMany('App\Indicator');
    }

    public function pages()
    {
        return $this->belongsToMany('App\Page')->withPivot('position');
    }
}
