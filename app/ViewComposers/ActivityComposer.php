<?php

namespace App\Http\ViewComposers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityComposer
{
    public function __construct(Request $request) 
    {
        $this->activity = $request->route('activity');
    }

    /**
     * Composes all views with $activity, based on route parameters, +
     * $canEdit, based on Authed user's role within $activity. 
     * 
     * @return view
     */
    public function compose(View $view)
    {
        $canEdit = false;

        if (!is_null($this->activity)) {
            $canEdit = $this->activity->pivot->role == 'staff' ? true : false;
        }

        $view->with('activity', $this->activity)
             ->with('canEdit', $canEdit);
    }
}