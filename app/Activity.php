<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'close_date', 'format', 'name', 'open_date', 'resource_link_record_id', 'consumer_pk',
    ];

    protected $hidden = [
        'consumer_pk', 'resource_link_record_id', 'status'
    ];

    public function choices()
    {
        return $this->hasMany('App\Choice');
    }

    /**
     * Returns true if the current date falls within the activity's open_date
     * and close_date, if they are set, or true if they are not set.
     * @return bool
     */
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
        return $this->belongsToMany('App\User')->withPivot(['complete', 'current_page', 'current_round', 'lti_user_id', 'role'])->withTimestamps();
    }
}
