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
        // todo: sort eager loading below
        $students = $this->activity->users->where('pivot.role', 'student')->sortBy('email');
        $rounds = $this->activity->rounds;

        foreach ($students as $student) {
            $round = $rounds->where('round_number', $student->pivot->current_round)->first();
            $student->completion = $student->getCompletion($round) * 100 . '%';
        }

        $view->with('students', $students);
    }
}