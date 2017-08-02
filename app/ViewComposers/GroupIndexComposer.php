<?php

namespace App\ViewComposers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupIndexComposer
{
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
        $groups = $this->activity->groups->sortBy('name');
        $groups->load('activityUsers.user');
        $view->with('groups', $groups);
    }
}