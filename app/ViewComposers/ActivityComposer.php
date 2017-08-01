<?php

namespace App\ViewComposers;

use App\Reflect\Reflect;
use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityComposer
{
    protected $activity;
    protected $format;
    protected $reflect;

    public function __construct(Request $request, Reflect $reflect) 
    {
        $this->activity = $request->route('activity');
        $this->format = $request->route('format');
        $this->reflect = $reflect;
    }

    /**
     * Composes all views with $activity from route parameters + home
     * link depending on user role + current format
     * 
     * @return view
     */
    public function compose(View $view)
    {
        $view->with('activity', $this->activity)
             ->with('homeUrl', $this->getHomeUrl());
    }

    private function getHomeUrl()
    {
        $homeUrl = isset($this->activity) ? 'a/' . $this->activity->id : '';

        if (Auth::check() && Auth::user()->role != 'staff') {
            $format = isset($this->format) ? $this->format : $this->reflect->getCurrentRoundFormat();

            $homeUrl .= '/' . $format;
        }

        return $homeUrl;
    }
}