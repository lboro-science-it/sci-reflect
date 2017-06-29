<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'close_date', 'format', 'name', 'open_date'
    ];

    protected $hidden = [
        'consumer_pk', 'resource_link_record_id', 'status'
    ];

    public function isOpen()
    {
        if ($this->status == 'open') {
            $now = new DateTime();
            $from = new DateTime($this->open_date);
            $to = new DateTime($this->close_date);
            if ($now >= $from && $now <= $to) {
                return true;
            }
        }

        return false;
    }

    public function pages()
    {
        return $this->hasMany('App\Page');
    }

    public function rounds()
    {
        return $this->hasMany('App\Round');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot(['complete', 'current_page_id', 'current_round_id', 'lti_user_id', 'role']);
    }
}
