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
     * Gets completion (as percentage) of $round or $page within $round
     *
     * @return decimal
     */
    public function getCompletion($round, $page = null)
    {
        $indicators = is_null($page) ? $round->getIndicators() : $page->getIndicators();
        $completion = $this->getIndicatorsCompletionDecimal($indicators, $round);

        if (is_null($completion)) {
            return null;
        }

        return number_format($completion * 100, 1) . '%';
    }

    public function getCompletionDecimal($round, $page = null)
    {
        $indicators = is_null($page) ? $round->getIndicators() : $page->getIndicators();
        return $this->getIndicatorsCompletionDecimal($indicators, $round);
    }

    /**
     * Gets completion (as a percentage) of indicators within round
     *
     * @return decimal
     */
    public function getIndicatorsCompletion($indicators, $round)
    {
        $completion = $this->getIndicatorsCompletionDecimal($indicators, $round);

        if (is_null($completion)) {
            return null;
        }

        return number_format($completion * 100, 1) . '%';
    }

    /**
     * Gets completion (as a decimal between 0 and 1) of indicators within round
     *
     * @return decimal
     */
    public function getIndicatorsCompletionDecimal($indicators, $round)
    {
        if ($indicators->count() > 0) {
            $selections = $this->selections->where('round_id', $round->id)
                                         ->whereIn('indicator_id', $indicators->pluck('id'));

            return $selections->count() / $indicators->count();
        }

        return null;
    }

    /**
     * Returns ratings for the given round creating if none exist
     *
     * @return decimal
     */
    public function getRatings($round)
    {
        $ratings = $this->ratings->where('round_id', $round->id);

        if (!$ratings->count()) {
            $ratings = app('RatingsHelper')->createRatings($round, $this);
        }

        return $ratings;
    }

    public function hasCompleted($round, $page = null)
    {
        return $this->getCompletionDecimal($round, $page) == 1;
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