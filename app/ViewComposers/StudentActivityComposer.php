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
     * Prepares data required to render the student activity dashboard, based
     * on the current round's format (or the Activity's format if there's
     * no current round)
     *
     * @return View
     */
    public function compose(View $view)
    {
        $this->eagerLoad();
        $formatClass = $this->getFormatClass();
        $activityData = $formatClass->composeActivity($this->activity, Auth::user());

        $view->with('activityData', $activityData);
    }

    /**
     * Eager loads various models that will be needed when rendering the
     * student activity dashboard, regardless of format.
     * @return void
     */
    private function eagerLoad()
    {
        // load stuff required for calculating round completion status, etc.
        $this->activity->load([
            'rounds.pages.skills.indicators'
        ]);
    }

    /**
     * Returns the FormatClass of the current round's format, or the 
     * activity's if there is no current round.
     * @return FormatClass
     */
    private function getFormatClass()
    {
        $round = $this->activity->rounds->where('round_number', $this->activity->pivot->current_round)->first();
        if (!is_null($round)) {
            return app($round->format);
        } else {
            return app($this->activity->format);
        }

    }
}