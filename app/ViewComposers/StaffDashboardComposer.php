<?php

namespace App\ViewComposers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDashboardComposer
{
    protected $activity;

    public function __construct(Request $request) 
    {
        $this->activity = $request->route('activity');
    }

    /**
     * Composes view with a list of $activity's students, staff, rounds, groups 
     * for rendering purposes.
     * 
     */
    public function compose(View $view)
    {
        $groups = $this->activity->getGroupListArray();
        $rounds = $this->activity->getRoundListArray();
        $students = $this->activity->getStudentListArray();
        $staff = $this->activity->getStaffListArray();

        $view->with('students', $students)
             ->with('staff', $staff)
             ->with('rounds', $rounds)
             ->with('groups', $groups);
    }
}