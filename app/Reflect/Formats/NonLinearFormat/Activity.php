<?php

namespace App\Reflect\Formats\NonLinearFormat;

use App\Reflect\Formats\BaseActivity;
use Illuminate\Http\Request;
use stdClass;

class Activity extends BaseActivity
{
    protected $view = 'activity.nonlinear.show';

    private function eagerLoad()
    {
        $this->activity->loadIndicatorsWithCategory();
    }

    private function getCategories()
    {
        $skills = $this->currentRound->getSkills();
        // gets all of the categories in the current round sorted by their number...
        $categories = $skills->pluck('category')->unique()->sortBy('number');

        // now calculate completion of each category


        return $categories;
        // todo get completion
    }

    private function getData()
    {
        $this->eagerLoad();

        $activityData = new stdClass();

        $activityData->view = $this->view;
        $activityData->categories = $this->getCategories();
        $activityData->chartData = $this->getChartData();

        return $activityData;
    }

    public function processActivity()
    {
        // draw a box for each of them
        // calculate category's completion based on selections & indicators (per calculating round completion / page completion)
        // so todo: move completion into a comparison between selections and indicators, called from page/round/whatever
        // that's it really
        return view($this->activityView)
               ->with('activityData', $this->getData());
    }
}