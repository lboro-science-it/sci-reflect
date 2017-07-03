<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\RatingsHelper;
use App\Round;
use Illuminate\Http\Request;

class StudentChartController extends Controller
{
    protected $ratingsHelper;

    public function __construct(RatingsHelper $ratingsHelper)
    {
        $this->ratingsHelper = $ratingsHelper;
    }

    public function show(Round $round)
    {
        $data = $this->ratingsHelper->getChartData($round, Auth::user());

        return view('chart.single')
        ->with('data', $data);
    }

}
