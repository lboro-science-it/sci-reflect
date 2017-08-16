<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\UserHelper;
use App\User;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Returns the view with forms for adding staff and students.
     *
     * @param  App\Activity $activity
     * @return View
     */
    public function create(Activity $activity, Request $request)
    {
        return view('activity.staff.users');
    }

    /**
     * Creates staff and student users where required, creates relationships
     * with this activity where required, return redirect to activity view.
     *
     * @param  App\Activity $activity
     * @return View
     */
    public function store(Activity $activity, Request $request, UserHelper $userHelper)
    {
        $message = $userHelper->processRequest($request);

        return redirect('a/' . $activity->id)
                  ->with('message', $message);
    }
}
