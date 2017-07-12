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
        $chartHelper = new ChartHelper($round, Auth::user());
        $chartData = $chartHelper->getChartData();

        $skillsHelper = new SkillsHelper($round, Auth::user());
        $strongestSkills = $skillsHelper->getStrongestSkills();
        $weakestSkills = $skillsHelper->getWeakestSkills();

        return view('chart.single')
        ->with('chartData', $chartData)
        ->with('strongestSkills', $strongestSkills)
        ->with('weakestSkills', $weakestSkills);
    }

}
