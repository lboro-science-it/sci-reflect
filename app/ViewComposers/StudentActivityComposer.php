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

    public function compose(View $view)
    {
        $currentRound = $this->activity->pivot->current_round;
        $currentPage = $this->activity->pivot->current_page;
        if (is_null($currentRound) || is_null($currentPage)) {
            $resumeLink = false;
        } else {
            $resumeLink = url('a/' . $this->activity->id . '/r/' . $currentRound . '/p/' . $currentPage);
        }
        $view->with('resumeLink', $resumeLink);
    }
}