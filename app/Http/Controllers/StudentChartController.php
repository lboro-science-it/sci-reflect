<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\ChartHelper;
use App\Round;
use Auth;
use Illuminate\Http\Request;

class StudentChartController extends Controller
{
    public function show(Activity $activity, Round $round)
    {
        $chartHelper = new ChartHelper($round, Auth::user());
        $chartData = $chartHelper->getChartData();

        return view('chart.single')
        ->with('chartData', $chartData);
    }

}
