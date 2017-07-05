<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\ChartHelper;
use App\Round;
use Auth;
use Illuminate\Http\Request;

class StudentChartController extends Controller
{
    protected $chartHelper;

    public function __construct(ChartHelper $chartHelper)
    {
        $this->chartHelper = $chartHelper;
    }

    public function show(Activity $activity, Round $round)
    {
        $chartData = $this->chartHelper->getChartData($round, Auth::user());

        return view('chart.single')
        ->with('chartData', $chartData);
    }

}
