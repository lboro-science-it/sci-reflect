<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function pages()
    {
        return $this->belongsToMany('App\Page')->withPivot(['page_number']);
    }
}
