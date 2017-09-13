<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    public function choice()
    {
        return $this->belongsTo('App\Choice');
    }

    public function skill()
    {
        return $this->belongsTo('App\Skill');
    }
}
