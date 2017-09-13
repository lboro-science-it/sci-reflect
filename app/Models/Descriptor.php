<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    protected $visible = [
        'choice_id',
        'skill_id',
        'text'
    ];

    public function choice()
    {
        return $this->belongsTo('App\Choice');
    }

    public function skill()
    {
        return $this->belongsTo('App\Skill');
    }
}
