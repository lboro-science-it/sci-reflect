<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockPage extends Model
{
    protected $table = 'block_page';
    
    protected $visible = [
        'block_id',
        'position'
    ];
}
