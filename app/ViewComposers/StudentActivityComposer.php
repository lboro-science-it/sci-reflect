<?php

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentActivityComposer
{
    public function __construct(Request $request) 
    {
        $this->activity = $request->route('activity');
    }

    /**
     * Prepares data required to render the student activity dashboard.
     *
     * @return View
     */
    public function compose(View $view)
    {
        $this->eagerLoad();

        // get current round format class
        $round = $this->activity->rounds->where('round_number', $this->activity->pivot->current_round)->first();

        if (!is_null($round)) {
            $formatClass = app($round->format);
        } else {
            $formatClass = app($this->activity->format);
        }

        $activityData = $formatClass->composeActivity($this->activity, Auth::user());

        $view->with('activityData', $activityData);
    }

    private function eagerLoad()
    {
        // load stuff required for calculating round completion status, etc.
        $this->activity->load([
            'rounds',
            'rounds.pages',
            'rounds.pages.skills',
            'rounds.pages.skills.indicators'
        ]);
    }
}