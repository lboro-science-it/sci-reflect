<?php

namespace App\Http\Controllers;

use App\Activity;
use Auth;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Set status of activity to closed, returns activity dashboard view.
     *
     * @return View
     */
    public function close(Activity $activity)
    {
        $activity->status = 'closed';
        $activity->save();

        return view('activity.staff.dashboard');
    }

    /**
     * Handles a submitted activity create form, generating default content.
     *
     * @return View
     */
    public function create(Request $request, Activity $activity)
    {
        $activity->update($request->input());
        $activity->status = 'design';
        
        $round = new \App\Round;
        $round->format = $activity->format;
        $round->round_number = 1;
        $round->title = 'Round 1';
        $activity->rounds()->save($round);

        $page = new \App\Page;
        $page->title = 'Intro Page';
        $activity->pages()->save($page);
        $round->pages()->attach($page->id, ['page_number' => 1]);

        $activity->save();

        return view('activity.staff.design');
    }

    /**
     * Logs the authed user out of the application.
     *
     * @return View
     */
    public function eject()
    {
        Auth::logout();

        // todo: add messaging
        return view('eject');
    }

    /**
     * Sets status of activity to open, returns staff activity dashboard.
     *
     * @return View
     */
    public function open(Activity $activity)
    {
        $activity->status = 'open';
        $activity->save();

        return view('activity.staff.dashboard');
    }

    /**
     * Returns activity view according to status of activity.
     *
     * @return View
     */
    public function show(Activity $activity)
    {
        if ($activity->status == 'new') {
            return view('activity.staff.new');
        }

        if ($activity->status == 'design') {
            return view('activity.staff.design');
        }

        return view('activity.staff.dashboard');
    }

    public function showSetup(Activity $activity)
    {
        return dd('showSetup view');
    }
}
