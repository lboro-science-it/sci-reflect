<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $visible = [
        'id',
        'category_id',
        'title',
        'description',
        'block_id',
        'number',
        'indicators'
    ];

    public function block()
    {
        return $this->belongsTo('App\Block');
    }

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
