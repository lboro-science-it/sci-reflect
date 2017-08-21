<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $visible = [
        'id',
        'text',
        'number'
    ];

    public function selections()
    {
        return $this->hasMany('App\Selection');
    }

    public function skill()
    {
        return $this->belongsTo('App\Skill');
    }
}
