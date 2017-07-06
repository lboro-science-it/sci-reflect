<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function activities()
    {
        return $this->belongsToMany('App\Activity')->withPivot([
            'complete', 'current_page', 'current_round', 'lti_user_id', 'role'
        ])->withTimestamps();
    }

    public function incrementRound()
    {
        // todo: only do below if pivot exists (and other tests)

        $activity = request()->route('activity')->with('rounds')->first();

        $complete = true;
        $roundNumber = null;
        $pageNumber = null;

        if ($this->currentRound < $activity->rounds->count()) {
            $complete = false;
            $roundNumber = $this->currentRound + 1;
            $pageNumber = 1;
        }

        $this->activities()->updateExistingPivot($activity->id, [
            'current_round' => $roundNumber,
            'current_page' => $pageNumber,
            'complete' => $complete
        ]);
    }

    public function ratings()
    {
        return $this->hasMany('App\Rating');
    }

    public function selections()
    {
        return $this->hasMany('App\Selection');
    }
}
