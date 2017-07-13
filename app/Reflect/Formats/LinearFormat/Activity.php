<?php

namespace App\Reflect\Formats\LinearFormat;

use App\Reflect\BlockContentParser;
use App\Reflect\ChartHelper;
use App\Reflect\SkillsHelper;
use App\Reflect\Formats\BaseFormat;
use Auth;
use Illuminate\Http\Request;
use stdClass;

class Activity extends BaseFormat
{
    protected $activity = null;

    protected $user = null;

    protected $view = 'activity.linear.show';

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->activity = $request->route('activity');
        $this->user = Auth::user();

        $this->previousRound = $this->getPreviousRound();

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
    public function getActivityData()
    {
        //todo: get strongest skills from previous round
        //todo: get improve links for weakest skills from previous round
        $activityData = new stdClass();

        $activityData->activityView = $this->view;
        $activityData->chartData = $this->getChartData();
        $activityData->roundContent = $this->getRoundContent();
        $activityData->resumeLink = $this->getResumeLink();
        $activityData->rounds = $this->getRounds();

        $skillsHelper = new SkillsHelper($this->previousRound, $this->user);

        $activityData->strongestSkills = $skillsHelper->getStrongestSkills();
        $activityData->weakestSkills = $skillsHelper->getWeakestSkills();

        return $activityData;
    }

    /**
     * Returns data for displaying the last completed chart.
     *
     */
    private function getChartData()
    {
        if (isset($this->previousRound)) {
            $chartHelper = new ChartHelper($this->previousRound, $this->user);

            return $chartHelper->getChartData();
        }

        return null;
    }

    private function getPreviousRound()
    {
        $currentRoundNumber = $this->user->currentRound;

        if ($currentRoundNumber != 1) {
            if ($currentRoundNumber > 1) {
                return $this->activity->rounds->where('round_number', $currentRoundNumber - 1)->first();
            } else if (is_null($currentRoundNumber)) {
                return $this->activity->rounds->where('round_number', $this->activity->rounds->count())->first();
            }
        }

        return null;
    }

    private function getResumeLink()
    {
        $currentRoundNumber = $this->user->currentRound;
        $currentPageNumber = $this->user->currentPage;

        if (is_null($currentRoundNumber) || is_null($currentPageNumber)) {
            return false;
        }

        return url('a/' . $this->activity->id . '/student/r/' . $currentRoundNumber . '/p/' . $currentPageNumber);
    }

    private function getRoundContent()
    {
        $currentRound = $this->activity->rounds->where('round_number', $this->user->currentRound)->first();

        if (isset($currentRound->block)) {
            $blockContentParser = new BlockContentParser();
            return $blockContentParser->parse($currentRound->block->content);
        }

        return null;
    }

    private function getRounds()
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
                $round->completion = 100.0;
                $roundsData->completed->push($round);
            } elseif ($round->round_number == $currentRoundNumber) {
                $round->completion = $this->user->getCompletion($round) * 100;
                $roundsData->current = $round;
            } else {
                $round->completion = null;
                $roundsData->future->push($round);
            }
        }

        return $roundsData;
    }

}