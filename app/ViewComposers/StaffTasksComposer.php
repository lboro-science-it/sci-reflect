<?php

namespace App\ViewComposers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffTasksComposer
{
    protected $activity;

    public function __construct(Request $request) 
    {
        $this->activity = $request->route('activity');
    }

    /**
     * Add data related to task status to view.
     */
    public function compose(View $view)
    {
        $usersAdded = ($this->activity->users->count() > 1);

        $view->with('usersAdded', $usersAdded);
    }
}