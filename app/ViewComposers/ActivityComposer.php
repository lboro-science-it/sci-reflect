<?php

namespace App\ViewComposers;

use App\Reflect\Reflect;
use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityComposer
{
    protected $activity;
    protected $reflect;

    public function __construct(Request $request, Reflect $reflect) 
    {
        $this->activity = $request->route('activity');
        $this->reflect = $reflect;
    }

    /**
     * Composes all views with $activity from route parameters + home
     * link depending on user role + current format
     * 
     */
    public function compose(View $view)
    {
        $view->with('activity', $this->activity)
             ->with('homeUrl', $this->getHomeUrl());
    }

    /**
     * Provides the correct $homeUrl to all views, for use in the nav bar.
     * $homeUrl is just 'a/{activity}' for staff, and for students is
     * 'a/{activity}/[format}' enabling change of format by changing url.
     * 
     */
    private function getHomeUrl()
    {
        $homeUrl = isset($this->activity) ? 'a/' . $this->activity->id : '';

        if (Auth::check() && Auth::user()->role != 'staff') {
            $format = $this->reflect->getCurrentRoundFormat();

            $homeUrl .= '/' . $format;
        }

        return $homeUrl;
    }
}