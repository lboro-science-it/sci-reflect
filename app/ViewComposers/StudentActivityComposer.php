<?php

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentActivityComposer
{
    protected $request;

    public function __construct(Request $request) 
    {
        $this->request = $request;
        $this->activity = $request->route('activity');
        $this->user = Auth::user();
    }

    /**
     * Add data required to render the student view dashboard, depending on
     * current round format, or activity format failing that.
     *
     * @return View
     */
    public function compose(View $view)
    {
        // we will need all rounds' content to calculate completion
        $this->activity->load([
            'rounds.pages.skills.indicators'
        ]);

        // we will need user's selections to calculate completion
        $this->user->load([
            'selections'
        ]);

        $formatClass = $this->getFormatClass();
        $activityData = $formatClass->getActivityData($this->activity, $this->user);

        $view->with('activityData', $activityData);
    }

    /**
     * Returns the FormatClass of the current round's format, or the 
     * activity's if there is no current round.
     * @return FormatClass
     */
    private function getFormatClass()
    {
        $round = $this->activity->rounds->where('round_number', $this->user->currentRound)->first();

        $formatClassName = isset($round) ? $round->format : $this->activity->format;
        $formatClassName = '\App\Reflect\Formats\\' . $formatClassName . '\\Activity';

        return new $formatClassName($this->request);
    }
}