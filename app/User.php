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
            'complete', 'current_page', 'current_round', 'group_id', 'lti_user_id', 'role'
        ])->withTimestamps();
    }

    /**
     * Gets completion (as a decimal between 0 and 1) of $round or $page within $round
     *
     * @return decimal
     */
    public function getCompletion($round, $page = null)
    {
        // get the necessary indicators
        if (is_null($page)) {
            $indicators = $round->getIndicators();
        } else {
            $indicators = $page->getIndicators();
        }

        // get the user's selections for the above indicators
        $selections = $this->selections->where('round_id', $round->id)
                                       ->whereIn('indicator_id', $indicators->pluck('id'));

        if ($indicators->count() > 0) {
            return $selections->count() / $indicators->count();
        }

        // if there are 0 indicators then it's not completable
        return null;
    }

    public function hasCompleted($round, $page = null)
    {
        return $this->getCompletion($round, $page) == 1;
    }

    public function incrementRound()
    {
        // todo: only do below if pivot exists (and other tests)
        $activity = request()->route('activity');

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
