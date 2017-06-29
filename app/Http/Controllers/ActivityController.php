<?php

namespace App\Http\Controllers;

use App\Activity;
use Auth;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function close(Activity $activity)
    {
        // todo: checking user role
        $activity->status = 'closed';
        $activity->save();

        return view('activity.staff');
    }

    public function create(Request $request, Activity $activity)
    {
        // todo: checking user role
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

        return view('activity.design');
    }

    public function eject()
    {
        Auth::logout();

        // todo: add messaging
        return view('eject');
    }

    public function open(Activity $activity)
    {
        // todo: checking user role
        $activity->status = 'open';
        $activity->save();

        return view('activity.staff');
    }

    /**
     * Return activity view according to status of activity and user's role
     *
     * @return View
     */
    public function show(Activity $activity)
    {
        if (Auth::user()->pivot->role == 'staff') {
            if ($activity->status == 'new') {
                return view('activity.new');
            }
            if ($activity->status == 'design') {
                return view('activity.design');
            }
            return view('activity.staff');
        }

        if (Auth::user()->pivot->role == 'student') {
            if ($activity->isOpen()) {
                return view('activity.student');
            }
        }

        return view('activity.closed');
    }
}
