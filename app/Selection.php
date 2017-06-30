<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    public function indicator()
    {
        return $this->belongsTo('App\Indicator');
    }
}
