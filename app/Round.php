<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function getCompletion($user)
    {
        $selectionsHelper = app('SelectionsHelper');
        $indicators = $this->getIndicators();
        $selections = collect($selectionsHelper->getSelectionsFromIndicators($indicators, $this, $user));
        $totalSelected = $selections->filter(function ($value) {
            return !is_null($value);
        })->count();

        return $totalSelected / count($indicators);
    }

    public function getIndicators()
    {
        $indicators = array();

        foreach($this->pages as $page) {
            $indicators = array_merge($indicators, $page->getIndicators());
        }

        return $indicators;
    }

    public function isComplete($user)
    {
        $selectionsHelper = app('SelectionsHelper');
        $indicators = $this->getIndicators();
        $selections = collect($selectionsHelper->getSelectionsFromIndicators($indicators, $this, $user));
        return !$selections->contains(null);
    }

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

    public function previousRoundComplete($user)
    {
        if ($this->round_number > 1) {
            $activity = request()->route('activity');
            $previousRound = $activity->rounds->where('round_number', $this->round_number - 1)->first();

            return $previousRound->isComplete($user);
        }

        return true;
    }
}
