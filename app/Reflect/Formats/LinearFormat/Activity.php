<?php

namespace App\Reflect\Formats\LinearFormat;

use App\Reflect\BlockContentParser;
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
     * Returns data to render Linear format Activity dashboard. We know that
     * the StudentActivityComposer will provide us access to $activity->pivot,
     * $activity->rounds, $round->pages, $page->skills, $skill->indicators. 
     * @return bool
     */
    public function getData()
    {
        $this->eagerLoad();

        //todo: get improve links for weakest skills from previous round
        $activityData = new stdClass();

        $activityData->view = $this->view;
        $activityData->chartData = $this->getChartData();
        $activityData->roundContent = $this->getRoundContent();
        $activityData->resumeLink = $this->getResumeLink();
        $activityData->rounds = $this->getRounds();

        $skillsHelper = app('SkillsHelper');

        $activityData->strongestSkills = $skillsHelper->getStrongestSkills($this->previousRound, $this->user);
        $activityData->weakestSkills = $skillsHelper->getWeakestSkills($this->previousRound, $this->user);

        return $activityData;
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

    private function getRoundContent()
    {
        if (isset($this->currentRound->block)) {
            $blockContentParser = new BlockContentParser();
            return $blockContentParser->parse($currentRound->block->content);
        }

        return null;
    }

    public function processActivity()
    {
        return view($this->activityView)
               ->with('activityData', $this->getData());
    }

}