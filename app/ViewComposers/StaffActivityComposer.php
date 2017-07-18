<?php

namespace App\Http\ViewComposers;

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
     * Composes view with a HTML-form friendly list of the available formats.
     * @return view
     */
    public function compose(View $view)
    {
        $students = $this->activity->users->where('pivot.role', 'student')->sortBy('email');
        $students->load([
            'selections'
        ]);

        $rounds = $this->activity->rounds->sortBy('title');
        $rounds->load([
            'pages.skills.indicators'
        ]);

        $groups = $this->activity->groups;

        foreach ($students as $student) {
            $student->group = $groups->where('id', $student->pivot->group_id)->first();
        }

        $view->with('students', $students)
             ->with('rounds', $rounds)
             ->with('groups', $groups);
    }
}