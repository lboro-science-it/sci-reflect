<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\UserHelper;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Activity $activity)
    {
        $groups = $activity->getGroupListArray();
        $rounds = $activity->getRoundListArray();
        $students = $activity->getStudentListArray();
        $staff = $activity->getStaffListArray();
        
        // get the user's group
        $activeGroupId = $activity->users->where('id', Auth::user()->id)->first()->pivot->group_id;

        return view('staff.users')->with('students', $students)
                                  ->with('staff', $staff)
                                  ->with('rounds', $rounds)
                                  ->with('groups', $groups)
                                  ->with('activeGroupId', $activeGroupId);
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

        return redirect('a/' . $activity->id . '/users')
                  ->with('message', $message);
    }

    /**
     * Updates the group which the user is related to, the endpoint of changing
     * the drop down box in the StudentRow.vue component.
     *
     * @param  App\Activity $activity
     * @return View
     */
    public function updateGroup(Activity $activity, $userId, Request $request)
    {
        $groupId = $request->input('groupId');
        $activity->users()->updateExistingPivot($userId, [
            'group_id' => ($groupId != 'null') ? $groupId : null
        ]);

        return $request->input('groupId');
    }

}
