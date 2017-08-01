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
        // manually relate group to each user based on pivot to activity
        $groups = $this->activity->groups;
        foreach ($this->activity->users as $user) {
            $user->setRelation('group', $groups->where('id', $user->pivot->group_id)->first());
        }

        // get activity students
        $students = $this->activity->users->where('pivot.role', 'student')->sortBy('email');
        $students->load([
            'selections'
        ]);

        // get activity staff
        $staff = $this->activity->users->where('pivot.role', 'staff')->sortBy('email');

        // load indicators, required for completion data
        $this->activity->rounds->load([
            'pages.skills.indicators'
        ]);
        $rounds = $this->activity->rounds->sortBy('title');

        $view->with('students', $students)
             ->with('staff', $staff)
             ->with('rounds', $rounds)
             ->with('groups', $groups);
    }
}