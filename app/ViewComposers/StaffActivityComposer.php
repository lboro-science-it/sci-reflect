<?php

namespace App\ViewComposers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffActivityComposer
{
    protected $activity;

    public function __construct(Request $request) 
    {
        $this->activity = $request->route('activity');
    }

    /**
     * Composes view with a list of $activity's students, rounds, groups 
     * for rendering purposes.
     * @return view
     */
    public function compose(View $view)
    {
        $groups = $this->activity->groups;
        $rounds = $this->activity->getRoundsListArray();
        $students = $this->activity->getStudentListArray();
        $staff = $this->activity->getStaffListArray();

        $view->with('students', $students)
             ->with('staff', $staff)
             ->with('rounds', $rounds)
             ->with('groups', $groups);
    }
}