<?php

namespace App\Http\Controllers;

use App\Activity;
use DB;
use Illuminate\Http\Request;

class StudentUserController extends Controller
{
    public function bulkGroup(Activity $activity, $groupId, Request $request)
    {
        // get the users whose group we need to change
        $users = $activity->users->whereIn('id', $request->input('students'));

        // get the pivot record ids where the group relationship is stored
        $pivotsToUpdate = [];
        foreach($users as $user) {
            array_push($pivotsToUpdate, 
                $user->pivot->id
            );
        }

        // do the update
        DB::table('activity_user')->whereIn('id', $pivotsToUpdate)->update([
            'group_id' => $groupId
        ]);

        return 'success';
    }

    public function updateGroup(Activity $activity, $userId, Request $request)
    {
        $activity->users()->updateExistingPivot($userId, [
            'group_id' => $request->input('groupId')
        ]);

        return $request->input('groupId');
    }
}
