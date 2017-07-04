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
        // get strongest skills
        // get improve links for weakest skills
        // get closed message

        $activityData = new stdClass();

        $activityData->activityView = $this->view;
        $activityData->rounds = $this->getRounds();
        $activityData->resumeLink = $this->getResumeLink();

        return $activityData;
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
        $roundsData->completed = array();
        $roundsData->future = array();

        foreach($rounds as $round) {
            if (is_null($currentRoundNumber) || $round->round_number < $currentRoundNumber) {
                $round->completion = "100";
                array_push($roundsData->completed, $round);
            } elseif ($round->round_number == $currentRoundNumber) {
                $round->completion = $round->getCompletion($this->user);
                $roundsData->current = $round;
            } else {
                $round->completion = null;
                array_push($roundsData->future, $round);
            }
        }

        return $roundsData;
    }

    private function getSidebar()
    {
        // get all $this->activity's rounds
        // mark all rounds < current round complete
        // get current round progress

    }

}