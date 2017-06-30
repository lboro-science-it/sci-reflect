<?php

namespace App\Http\ViewComposers;

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
        // todo: get current round object, depending on format get the content
        // for the view i.e. multiple categories w/ resume links or just the round resume link
        // get percentage completion, selections, etc.

        $currentRound = $this->activity->pivot->current_round;
        $currentPage = $this->activity->pivot->current_page;
        if (is_null($currentRound) || is_null($currentPage)) {
            $resumeLink = false;
        } else {
            $resumeLink = url('a/' . $this->activity->id . '/student/r/' . $currentRound . '/p/' . $currentPage);
        }
        $view->with('resumeLink', $resumeLink);
    }
}