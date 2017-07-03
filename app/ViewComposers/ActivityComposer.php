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

    public function compose(View $view)
    {
        $canEdit = $this->activity->role == 'staff' ? true : false;
        $view->with('activity', $this->activity)
             ->with('canEdit', $canEdit);
    }
}