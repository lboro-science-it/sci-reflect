<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Page;
use App\Round;
use Auth;
use Illuminate\Http\Request;

class LinearController extends Controller
{
    /**
     * Display the dashboard for the linear activity.
     *
     * @return View or Redirect
     */
    public function dashboard(Activity $activity)
    {
        if ($activity->isOpen()) {
            $linearActivity = app('LinearActivity');

            return $linearActivity->processActivity();
        }

        // todo: move to middleware affecting all student activity routes
        return view('activity.closed');
    }

    /**
     * Process submitted data and return a single page as appropriate.
     *
     * @return View or Redirect
     */
    public function page(Activity $activity, Round $round, Page $page)
    {
        $linearPage = app('LinearPage');
        
        return $linearPage->processPage($round, $page, Auth::user());
    }
}
