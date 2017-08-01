<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display the form for managing groups.
     */
    public function index(Activity $activity)
    {
        $groups = $activity->groups()->orderBy('name')->get();

        return view('activity.staff.groups')
             ->with('groups', $groups);
    }
}
