<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'close_date', 'format', 'name', 'open_date'
    ];

    protected $hidden = [
        'consumer_pk', 'resource_link_record_id', 'status'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot(['complete', 'current_page_id', 'current_round_id', 'lti_user_id', 'role']);
    }
}
