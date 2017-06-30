<?php

namespace App\Http\ViewComposers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageComposer
{
    protected $request;

    public function __construct(Request $request) 
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $page = $this->request->route('page');
        $round = $this->request->route('round');
        $activity = $this->request->route('activity');
        $view->with('page', $page)
             ->with('round', $round)
             ->with('activity', $activity);
    }
}