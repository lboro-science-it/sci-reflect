<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Placeholder for accepting a text list of staff email addresses to
     * create as users and relate to the activity.
     * todo: extract method to some kind of user creation method.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Activity $activity
     * @return View
     */    
    public function postStaff(Request $request, Activity $activity)
    {

    }

    /**
     * Register a bunch of users to the activity as students from a pasted
     * textarea input of email addresses on newlines.
     * todo: extract method refactoring so use in postStaff + postStudents
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Activity $activity
     * @return Redirect
     */
    // todo: validate email before adding
    public function postStudents(Request $request, Activity $activity)
    {
        // split the textarea students input into an array of email addresses
        $studentEmails = collect(preg_split('/\r\n|[\r\n]/', $request->input('students')));

        // get users who already exist in the database
        $existingUsers = User::whereIn('email', $studentEmails)->get();

        // get existing users who need to be related to this activity
        $existingActivityUsers = $activity->users()->whereIn('user_id', $existingUsers->pluck('id'))->get();
        $usersToRelate = $existingUsers->diff($existingActivityUsers);

        // get emails of users we need to create (+ relate to this activity)
        $usersToCreate = $studentEmails->diff($existingUsers->pluck('email'));

        $createdUsers = collect(array());

        // insert all $usersToCreate in a single insert statement
        if ($usersToCreate->count() > 0) {
            $usersToInsert = [];
            foreach($usersToCreate as $userEmail) {
                array_push($usersToInsert, [
                    'email' => $userEmail,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            DB::table('users')->insert($usersToInsert);
            
            $createdUsers = User::whereIn('email', $usersToCreate)->get();
        }

        // merge with the pre-existing users to relate
        $usersToRelate = $createdUsers->merge($usersToRelate);

        // insert all relationships in a single insert statement
        if ($usersToRelate->count() > 0) {
            $pivotsToInsert = [];
            foreach($usersToRelate as $user) {
                array_push($pivotsToInsert, [
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'role' => 'student',
                    'current_page' => 1,
                    'current_round' => 1,
                    'complete' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            DB::table('activity_user')->insert($pivotsToInsert);
        }

        $message = $usersToCreate->count() . ' students created.';
        $message .= ' ' . $existingUsers->count() . ' students already existed.';
        $message .= ' ' . $usersToRelate->count() . ' students linked.';

        return redirect('a/' . $activity->id)
             ->with('message', $message);
    }

    /**
     * Placeholder for function to display the form for adding students.
     *
     * @param  App\Activity $activity
     * @return View
     */
    public function showAddStudents(Activity $activity)
    {
        return dd('showAddStudents view here');
    }
}
