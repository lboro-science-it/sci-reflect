<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
use App\Page;
use App\Round;
use Illuminate\Http\Request;

class NonLinearController extends Controller
{
    /**
     * Display the dashboard for the nonlinear activity.
     *
     * @param  App\Activity $activity
     * @return View or Redirect
     */
    public function dashboard(Activity $activity)
    {
        if ($activity->isOpen()) {
            $nonLinearActivity = app('NonLinearActivity');

            return $nonLinearActivity->processActivity();
        }

        // todo: move to middleware affecting all student activity routes
        return view('activity.closed');
    }

    /**
     * Process the submitted data and return the response from the 
     * NonLinearPage class, such as the next page.
     *
     * @param  App\Activity $activity
     * @param  App\Round $round
     * @param  App\Category $category
     * @param  App\Page $page
     * @return View or Redirect
     */
    public function page(Activity $activity, Round $round, Category $category, Page $page)
    {
        $nonLinearPage = app('NonLinearPage');
        
        return $nonLinearPage->processPage($round, $category, $page, Auth::user());
    }
}
