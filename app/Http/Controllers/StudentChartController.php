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
    public function show(Activity $activity, Round $round)
    {
        $chartHelper = app('ChartHelper');
        $chartData = $chartHelper->getChartData($round, Auth::user());

        $skillsHelper = new SkillsHelper($round, Auth::user());
        $skills = $skillsHelper->getSkills();

        return view('chart.single')
        ->with('chartData', $chartData)
        ->with('skills', $skills);
    }

}
