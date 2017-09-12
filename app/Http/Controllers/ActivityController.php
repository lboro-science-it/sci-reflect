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
     * If $request->input('clone_from') is set we need to clone that activity's
     * content into this activity.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Activity $activity
     * @return View
     */
    public function create(Request $request, Activity $activity)
    {
        $activity->update($request->input());
        $activity->status = 'design';

        // ok, this is a pretty dang ugly conditional block...
        // since the 'clone from' and 'create from json' options were added late,
        // I don't have time to make this neater.
        // So the first one checks if the 'clone from' select was set, if so it does that
        // second one checks if any json was pasted, if so, create from that
        // third, finally, manually create a few dummy objects. Frankly the third is useless since
        // I didn't finish building the GUI.

        if ($request->has('clone_from') && $request->input('clone_from') != 'null') {
            // $request->input('clone_from') contains the activity ID to clone the content of
            // check it's one of the user's activities
            $sourceActivity = Auth::user()->activities()->where('activity_id', $request->input('clone_from'))->first();

            // kick the user out if they are trying to clone someone else's activity
            if (!isset($sourceActivity)) {
                return redirect('eject');
            }

            $activity->cloneFrom($sourceActivity);
        } else if ($request->input('create_from_json') != '') {
            // $request->input('create_from_json') contains a json object detailing rounds, pages, categories, choices, etc to create
            // so we'll just chuck it to the $activity's $createFromJSON method
            $json = $request->input('create_from_json');
            $activity->createFromJSON($json);
        } else {
            $round = new \App\Round;
            $round->format = $activity->format;
            $round->round_number = 1;
            $round->title = 'Round 1';
            $activity->rounds()->save($round);

            $page = new \App\Page;
            $page->title = 'Intro Page';
            $activity->pages()->save($page);
            $round->pages()->attach($page->id, ['page_number' => 1]);
        }

        $activity->save();

        return view('staff.dashboard');
    }

    /**
     * Deals with the 'create from JSON' form in the Setup activity view.
     * Basically, it gets the json out of the request and chucks it at the 
     * $activity - so on the Activity model there is a corresponding 
     * createFromJSON method which goes through the json and creates stuff.
     *
     */
    public function createFromJSON(Activity $activity, Request $request)
    {
        if ($request->input('create_from_json') != '') {
            $json = $request->input('create_from_json');
            $activity->createFromJSON($json);
        }

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
        if ($activity->isNew()) {
            $reflect = app('Reflect');

            // get the user's other activities in case they want to clone the content
            $activities = Auth::user()->activities()
                                      ->where('activity_id', '<>', $activity->id)
                                      ->wherePivot('role', 'staff')
                                      ->with('rounds')
                                      ->with('skills')
                                      ->get();


            return view('staff.new')
                 ->with('formats', $reflect->getFormatDisplayNames())
                 ->with('activities', $activities);
        }

        return view('staff.dashboard');
    }

    /**
     * Display the view informing the user that the activity is closed.
     *
     * @return View
     */
    public function showClosed(Activity $activity)
    {
        return view('activity.closed');
    }

    /**
     * Display the view for activity setup (basically same as new but prepopulated)
     *
     * @return View
     */
    public function showSetup(Activity $activity)
    {
        // todo: pass the correct data etc
        return view ('staff.setup');
    }
}
