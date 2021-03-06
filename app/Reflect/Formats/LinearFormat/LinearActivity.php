<?php

namespace App\Reflect\Formats\LinearFormat;

use App\Reflect\Formats\BaseActivity;
use App\Reflect\MessageHelper;
use Auth;
use Illuminate\Http\Request;
use stdClass;

class LinearActivity extends BaseActivity
{
    protected $view = 'linear.dashboard';

    private function eagerLoad()
    {
        // todo: replace this block load with a load for block in the round +
        // block for each skill that will be displayed, all in one go
        $this->activity->rounds->load([
            'pages.skills.indicators',
            'pages.skills.block'
        ]);

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

        $activityData = new stdClass();

        $activityData->chartData = $this->getChartData();
        $activityData->hasDone = $this->hasDone();
        $activityData->roundContent = $this->getRoundContent();
        $activityData->resumeLink = $this->getResumeLink();
        $activityData->rounds = $this->activity->getRoundsData();

        $activityData->totalSkills = $this->getTotalSkills();
        $activityData->currentPageTitle = $this->getCurrentPageTitle();
        $activityData->statusMessage = $this->getStatusMessage($activityData->rounds->current);

        $skillsHelper = app('SkillsHelper');
        $activityData->strongestSkills = $skillsHelper->getUserSkills($this->previousRound, $this->user)->splice(0, 3);
        $activityData->weakestSkills = $skillsHelper->getUserSkills($this->previousRound, $this->user)->sortBy('rating')->splice(0, 3);

        return $activityData;
    }

    private function hasDone()
    {
        if (isset($this->currentRound) && $this->user->hasCompleted($this->currentRound)) {
            return true;
        }

        return false;
    }

    private function getCurrentPageTitle()
    {
        if (isset($this->currentRound)) {
            return $this->currentRound->pages->where('pivot.page_number', $this->user->currentPage)->first()->title;
        }
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

    private function getStatusMessage($currentRound)
    {
        if (isset($currentRound)) {
            $completionMessage = MessageHelper::getCompletionMessage($currentRound->completionDecimal);
            $timeMessage = MessageHelper::getTimeMessage($currentRound->close_date);

            $message = $completionMessage . $timeMessage;

            return $message;
        }
    }
    
    private function getTotalSkills()
    {
        if (isset($this->currentRound)) {
            return $this->currentRound->getSkills()->count();
        }
    }

    public function processActivity()
    {
        return view($this->view)
               ->with('activityData', $this->getData());
    }

}