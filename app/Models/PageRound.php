<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageRound extends Model
{
    protected $table = 'page_round';
    
    protected $visible = [
        'page_id',
        'page_number'
    ];
}
