<?php

namespace App\Reflect\Formats\LinearFormat;

use App\Reflect\Formats\BaseActivity;
use Auth;
use Illuminate\Http\Request;
use stdClass;

class Activity extends BaseActivity
{
    protected $view = 'activity.linear.show';

    private function eagerLoad()
    {
        $this->activity->loadIndicatorsWithCategory();

        if (isset($this->previousRound)) {
            $this->user->load([
                'ratings' => function($q) {
                    $q->whereIn('round_id', $this->activity->rounds->pluck('id'));
                }
            ]);
        }
    }

    /**
     * Returns data to render Linear format Activity dashboard. 
     *
     * @return stdClass
     */
    public function getData()
    {
        $this->eagerLoad();

        //todo: get improve links for weakest skills from previous round
        $activityData = new stdClass();

        $activityData->view = $this->view;
        $activityData->chartData = $this->getChartData();
        $activityData->hasDone = $this->hasDone();
        $activityData->roundContent = $this->getRoundContent();
        $activityData->resumeLink = $this->getResumeLink();
        $activityData->rounds = $this->getRounds();

        $skillsHelper = app('SkillsHelper');

        $activityData->strongestSkills = $skillsHelper->getStrongestSkills($this->previousRound, $this->user);
        $activityData->weakestSkills = $skillsHelper->getWeakestSkills($this->previousRound, $this->user);

        return $activityData;
    }

    private function hasDone()
    {
        if (isset($this->currentRound) && $this->user->hasCompleted($this->currentRound)) {
            return true;
        }

        return false;
    }

    private function getResumeLink()
    {
        $currentRoundNumber = $this->user->currentRound;
        $currentPageNumber = $this->user->currentPage;

        if (is_null($currentRoundNumber) || is_null($currentPageNumber)) {
            return false;
        }

        return url('a/' . $this->activity->id . '/linear/r/' . $currentRoundNumber . '/p/' . $currentPageNumber);
    }

    public function processActivity()
    {
        return view($this->activityView)
               ->with('activityData', $this->getData());
    }

}