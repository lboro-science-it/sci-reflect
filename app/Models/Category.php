<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $visible = [
        'id',
        'name',
        'color',
        'icon_href',
        'number'
    ];

    public function skills()
    {
        return $this->hasMany('App\Skill');
    }
}
