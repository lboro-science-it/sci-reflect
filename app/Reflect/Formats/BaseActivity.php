<?php

namespace App\Reflect\Formats;

use Auth;
use Illuminate\Http\Request;
use stdClass;

class BaseActivity
{
    protected $request;

    protected $activity = null;

    protected $user = null;

    protected $activityView = 'activity.student';

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->activity = $request->route('activity');
        $this->user = Auth::user();

        $this->currentRound = $this->activity->rounds->where('round_number', $this->user->currentRound)->first();
        $this->previousRound = $this->getPreviousRound();
    }
    
    /**
     * Returns data for displaying the last completed chart.
     *
     */
    public function getChartData()
    {
        if (isset($this->previousRound)) {
            $chartHelper = app('ChartHelper');

            return $chartHelper->getChartData($this->previousRound, $this->user);
        }

        return null;
    }

    private function getPreviousRound()
    {
        $currentRoundNumber = $this->user->currentRound;

        if ($currentRoundNumber > 1) {
            return $this->activity->rounds->where('round_number', $currentRoundNumber - 1)->first();
        } else if (is_null($currentRoundNumber)) {
            return $this->activity->rounds->where('round_number', $this->activity->rounds->count())->first();
        }

        return null;
    }

    public function getRounds()
    {
        $rounds = $this->activity->rounds->sortBy('round_number');
        $currentRoundNumber = $this->user->currentRound;

        $roundsData = new stdClass();

        $roundsData->completed = collect(array());
        $roundsData->future = collect(array());
        $roundsData->current = null;

        foreach($rounds as $round) {
            $round->viewable = $round->isViewable($this->user);
            if (is_null($currentRoundNumber) || $round->round_number < $currentRoundNumber) {
                $round->completion = '100%';
                $roundsData->completed->push($round);
            } elseif ($round->round_number == $currentRoundNumber) {
                $round->completion = $this->user->getCompletion($round);
                $roundsData->current = $round;
            } else {
                $round->completion = null;
                $roundsData->future->push($round);
            }
        }

        return $roundsData;
    }
}