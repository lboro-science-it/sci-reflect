<?php

namespace App\Http\Controllers;

use App\Activity;
use Auth;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Set status of activity to closed and return activity dashboard view.
     *
     * @param  App\Activity $activity
     * @return View
     */
    public function close(Activity $activity)
    {
        $activity->status = 'closed';
        $activity->save();

        return view('staff.dashboard');
    }

    /**
     * Handle a submitted activity create form and generate default content.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Activity $activity
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

        return view('staff.dashboard');
    }

    /**
     * Log the authed user out of the application.
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
     * Set status of activity to open and return staff activity dashboard.
     *
     * @param  App\Activity $activity
     * @return View
     */
    public function open(Activity $activity)
    {
        $activity->status = 'open';
        $activity->save();

        return view('staff.dashboard');
    }

    /**
     * Return activity view according to status of activity.
     *
     * @param  App\Activity $activity
     * @return View
     */
    public function show(Activity $activity)
    {
        if ($activity->status == 'new') {
            $reflect = app('Reflect');

            return view('staff.new')
                 ->with('formats', $reflect->getFormatDisplayNames());
        }

        return view('staff.dashboard');
    }

    public function showClosed(Activity $activity)
    {
        return view('activity.closed');
    }

    public function showSetup(Activity $activity)
    {
        $rounds = $activity->rounds->sortBy('round_number')->values()->toJson();
        $activity->pages->load([
            'blockPages',
            'pageSkills'
        ]);
        $pages = $activity->pages->toJson();
        
        $blocks = $activity->blocks->toJson();
        
        $activity->skills->load([
            'indicators'
        ]);
        $skills = $activity->skills->toJson();


        return view ('staff.setup')->with('rounds', $rounds)
                                   ->with('pages', $pages)
                                   ->with('blocks', $blocks)
                                   ->with('skills', $skills);
    }
}
