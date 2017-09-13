<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $visible = [
        'id',
        'value',
        'label'
    ];

    protected $guarded = [];

    public function descriptors()
    {
        return $this->hasMany('App\Descriptor');
    }
}
