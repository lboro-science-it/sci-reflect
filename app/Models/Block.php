<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $visible = [
        'id',
        'content'
    ];

    public function pages()
    {
        return $this->belongsToMany('App\Page')->withPivot('position');
    }
}
