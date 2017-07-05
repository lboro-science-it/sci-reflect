<?php

namespace App\Reflect\Formats\LinearFormat;

use stdClass;

class LinearFormatActivity
{
    protected $activity, $user;

    protected $view = 'activity.linear.show';

    public function __construct($activity, $user)
    {
        $this->activity = $activity;
        $this->user = $user;
    }

    public function getActivityViewData()
    {
        // get last chart
        // get strongest skills from previous round
        // get improve links for weakest skills from previous round
        $activityData = new stdClass();

        $activityData->activityView = $this->view;
        $activityData->rounds = $this->getRounds();
        $activityData->resumeLink = $this->getResumeLink();
        $activityData->chartData = $this->getChartData();

        return $activityData;
    }

    /**
     * Returns data for displaying the last completed chart.
     *
     */
    private function getChartData()
    {
        $currentRoundNumber = $this->activity->pivot->current_round;

        if ($currentRoundNumber != 1) {
            if ($currentRoundNumber > 1) {
                $previousRound = $this->activity->rounds->where('round_number', $currentRoundNumber - 1)->first();
            } else if (is_null($currentRoundNumber)) {
                $previousRound = $this->activity->rounds->where('round_number', $this->activity->rounds->count())->first();
            }
            $ratingsHelper = app('RatingsHelper');
            return $ratingsHelper->getChartData($previousRound, $this->user);
        }

        return null;
    }

    private function getResumeLink()
    {
        $currentRoundNumber = $this->activity->pivot->current_round;
        $currentPageNumber = $this->activity->pivot->current_page;

        if (is_null($currentRoundNumber) || is_null($currentPageNumber)) {
            return false;
        }

        return url('a/' . $this->activity->id . '/student/r/' . $currentRoundNumber . '/p/' . $currentPageNumber);
    }

    private function getRounds()
    {
        $rounds = $this->activity->rounds->sortBy('round_number');
        $currentRoundNumber = $this->activity->pivot->current_round;

        $roundsData = new stdClass();
        $roundsData->completed = collect(array());
        $roundsData->future = collect(array());
        $roundsData->current = null;

        foreach($rounds as $round) {
            $round->viewable = $round->isViewable($this->user);
            if (is_null($currentRoundNumber) || $round->round_number < $currentRoundNumber) {
                $round->completion = 100.0;
                $roundsData->completed->push($round);
            } elseif ($round->round_number == $currentRoundNumber) {
                $round->completion = $round->getCompletion($this->user) * 100;
                $roundsData->current = $round;
            } else {
                $round->completion = null;
                $roundsData->future->push($round);
            }
        }

        return $roundsData;
    }

}