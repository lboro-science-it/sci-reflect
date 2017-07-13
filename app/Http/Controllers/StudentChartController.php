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
        // todo: moving this into a central location called from both helpers
        $activity->rounds->load([
            'pages.skills.indicators',
            'pages.skills.category'
        ]);

        $chartHelper = app('ChartHelper');
        $chartData = $chartHelper->getChartData($round, Auth::user());

        $skillsHelper = app('SkillsHelper');
        $skills = $skillsHelper->getSkills($round, Auth::user());

        return view('chart.single')
        ->with('chartData', $chartData)
        ->with('skills', $skills);
    }

}
