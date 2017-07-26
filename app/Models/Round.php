<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function block()
    {
        return $this->belongsTo('App\Block');
    }

    /**
     * Returns an array of all indicators present in the round (via pages,
     * skills, indicators)
     * @return array
     */
    public function getIndicators()
    {
        if (!isset($this->indicators)) {
            $indicators = collect(array());

            foreach($this->pages as $page) {
                $indicators = $indicators->merge($page->getIndicators());
            }

            $this->indicators = $indicators->unique();
        }

        return $this->indicators;
    }

    public function getSkills()
    {
        if (!isset($this->skills)) {
            $skills = collect(array());

            foreach($this->pages as $page) {
                $skills = $skills->merge($page->skills);
            }

            $this->skills = $skills->unique('id');
        }

        return $this->skills;
    }

    /**
     * Rounds are only visible if previous rounds have been completed, and 
     * the current date is within their date boundaries (if set) OR if the
     * round is in the past, if keep_visible is set to true. 
     * @return bool
     */
    public function isViewable($user)
    {
        $now = new DateTime();
        $from = new DateTime($this->open_date);
        $to = new DateTime($this->close_date);

        if ($now >= $from && $now <= $to) {     // now is within round bounds
            if (!$this->previousRoundComplete($user)) {
                $this->notViewableReason = "Not available until previous round completed.";
                return false;
            }

            return true;
        }

        if ($now > $to) {                       // round is in the past
            if ($this->keep_visible) {
                return true;
            }

            $this->notViewableReason = "No longer available.";
            return false;
        }

        // round must be in the future
        $this->notViewableReason = "Not available until " . $from->format('H:i d/m/Y');
        return false;
    }

    public function pages()
    {
        return $this->belongsToMany('App\Page')->withPivot(['page_number']);
    }

    /**
     * Returns true if the previous round is complete, and also returns true
     * if current round is the first round.
     * @return bool
     */
    public function previousRoundComplete($user)
    {
        if ($this->round_number > 1) {
            $activity = request()->route('activity');
            $previousRound = $activity->rounds->where('round_number', $this->round_number - 1)->first();

            $user->hasCompleted($previousRound);
        }

        return true;
    }
}