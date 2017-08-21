<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageSkill extends Model
{
    protected $table = 'page_skill';
    
    protected $visible = [
        'skill_id',
        'position'
    ];
}
