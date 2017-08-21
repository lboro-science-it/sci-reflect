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
}
