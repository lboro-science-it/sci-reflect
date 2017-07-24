<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function indicators()
    {
        return $this->hasMany('App\Indicator');
    }

    public function pages()
    {
        return $this->belongsToMany('App\Page')->withPivot('position');
    }
}
