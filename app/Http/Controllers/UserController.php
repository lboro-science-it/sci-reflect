<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Register a bunch of users to the activity with the student role.
     *
     * @return View or Redirect
     */
    public function postStudents(Request $request, Activity $activity)
    {
        $students = collect(preg_split('/\r\n|[\r\n]/', $request->input('students')));

        $activityUsers = $activity->users()->get();

        // todo:
        // compare submitted students to activity's existing users
        // compare submitted students to User database to find which need creating
        // create submitted students that need creating
        // compare submitted students to activity's existing users to find which need relating
        // create pivot tables that need creating






        // emails that aren't current users associated with this activity
        $usersToCreate = $students->diff($activityUsers->pluck('email'));


        // get users who exist but need to be related to this activity
        $usersToRelate = User::whereIn('email', $usersToCreate)->get();

        // insert users that need creating, getting their ids

        // todo:


        // insert all users that need creating, get their ids
        // probably the way to do this is to insert them then select them

        // todo:
        // insert all pivot tables that need creating wth user_id, activity_id, role = student
        // (ensure everything else can be null until user logs in... therefore needs to be set on LTI launch)
        // (i.e. lti_user_id, perhaps setting current_page/round/complete is ok here though...)

    }
}
