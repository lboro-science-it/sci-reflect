<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\ChartHelper;
use App\Reflect\SkillsHelper;
use App\Round;
use Auth;
use Illuminate\Http\Request;

class StudentChartController extends Controller
{
    /**
     * Eager load necessary data then render the single chart view.
     *
     * @return View
     */    
    public function show(Activity $activity, Round $round)
    {
        $activity->rounds->load([
            'pages.skills.indicators',
            'pages.skills.block'
        ]);

        // get the round object with the eager loaded data
        $round = $activity->rounds->where('id', $round->id)->first();

        $chartHelper = app('ChartHelper');
        $chartData = $chartHelper->getChartData($round, Auth::user());

        $skillsHelper = app('SkillsHelper');
        $categories = $skillsHelper->getActivitySkillsInCategories($round, Auth::user());

        return view('chart.single')
        ->with('chartData', $chartData)
        ->with('categories', $categories)
        ->with('round', $round)
        ->with('rounds', $activity->getRoundsData());
    }

}
