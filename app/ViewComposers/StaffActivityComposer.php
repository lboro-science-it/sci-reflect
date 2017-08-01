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
     * Composes view with a list of $activity's students, rounds, groups 
     * for rendering purposes.
     * @return view
     */
    public function compose(View $view)
    {
        $this->activity->load([
            'users' => function($q) {
                $q->orderBy('email', 'asc');
            }
        ]);

        $students = $this->activity->users->where('pivot.role', 'student')->sortBy('email');
        $students->load([
            'selections'
        ]);

        $this->activity->rounds->load([
            'pages.skills.indicators'
        ]);
        $rounds = $this->activity->rounds->sortBy('title');

        $groups = $this->activity->groups;

        $users = $this->activity->users->sortBy('email');
        foreach ($users as $user) {
            $user->setRelation('group', $groups->where('id', $user->pivot->group_id)->first());
        }

        foreach ($students as $student) {
            $student->group = $groups->where('id', $student->pivot->group_id)->first();
        }

        $view->with('students', $students)
             ->with('users', $users)
             ->with('rounds', $rounds)
             ->with('groups', $groups);
    }
}