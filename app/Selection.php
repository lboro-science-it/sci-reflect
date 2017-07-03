<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    public function choice()
    {
        return $this->belongsTo('App\Choice');
    }

    public function indicator()
    {
        return $this->belongsTo('App\Indicator');
    }
}
