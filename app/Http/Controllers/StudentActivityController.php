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
    public function show(Activity $activity)
    {
        if ($activity->isOpen()) {
            return view('activity.student');
        }

        return view('activity.closed');
    }
}
