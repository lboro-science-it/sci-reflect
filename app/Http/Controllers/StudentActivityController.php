<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\Reflect;
use Auth;
use Illuminate\Http\Request;

class StudentActivityController extends Controller
{
    /**
     * Returns activity view according to status of activity.
     *
     * @return View
     */
    public function show(Activity $activity, $format, Request $request, Reflect $reflect)
    {
        if ($activity->isOpen()) {

            $formatClass = $reflect->getActivityFormatClass($format);

            return $formatClass->processActivity($activity);
        }

        return view('activity.closed');
    }

}
