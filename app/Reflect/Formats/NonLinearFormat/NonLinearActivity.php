<?php

namespace App\Reflect\Formats\NonLinearFormat;

use App\Reflect\Formats\BaseActivity;
use Illuminate\Http\Request;
use stdClass;

class NonLinearActivity extends BaseActivity
{
    protected $view = 'activity.nonlinear.show';

    private function eagerLoad()
    {
        $this->activity->rounds->load([
            'pages.skills.indicators',
            'pages.skills.category',
            'pages.skills.block'
        ]);
    }

    private function getCategories()
    {
        $skills = $this->currentRound->getSkills();
        // gets all of the categories in the current round sorted by their number...
        $categories = $skills->pluck('category')->unique()->sortBy('name')->sortBy('number');

        foreach ($categories as $category) {
            $categorySkills = $skills->where('category_id', $category->id);
            $indicators = collect(array());
            foreach($categorySkills as $skill) {
                $indicators = $indicators->merge($skill->indicators);
            }

            $category->completion = $this->user->getIndicatorsCompletion($indicators, $this->currentRound);
            $category->decCompletion = $this->user->getIndicatorsCompletionDecimal($indicators, $this->currentRound);
            $category->totalSkills = $categorySkills->count();
        }

        return $categories;
    }

    private function getData()
    {
        $this->eagerLoad();

        $activityData = new stdClass();

        $activityData->view = $this->view;
        $activityData->categories = $this->getCategories();
        $activityData->chartData = $this->getChartData();
        $activityData->roundContent = $activityData->roundContent = $this->getRoundContent();
        $activityData->rounds = $this->getRounds();
        $activityData->roundViewable = isset($activityData->rounds->current) && $activityData->rounds->current->viewable;

        return $activityData;
    }

    public function processActivity()
    {
        return view($this->activityView)
               ->with('activityData', $this->getData());
    }
}