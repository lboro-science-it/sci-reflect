<?php

namespace App\Http\Controllers;

use App\Activity;
use Auth;
use Illuminate\Http\Request;

class StudentActivityController extends Controller
{
    /**
     * Returns activity view according to status of activity.
     *
     * @return View
     */
    public function show(Activity $activity, Request $request)
    {
        if ($activity->isOpen()) {
            $round = $activity->rounds->where('round_number', Auth::user()->currentRound)->first();

            $formatName = isset($round) ? $round->format : $activity->format;
            $formatClassName = "\App\Reflect\Formats\\$formatName\Activity";

            $formatClass = new $formatClassName($request);

            return $formatClass->processActivity($activity);
        }

        return view('activity.closed');
    }
}
