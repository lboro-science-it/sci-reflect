<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Group;
use Auth;
use DB;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Add a bunch of users ($request->input('users')) to a given $groupId,
     * i.e. update records on 'activity_user' table, which contains a 'group_id'
     * field. This is the endpoint of StudentTable.vue component's bulk user
     * changing form. $request->input('users') is just an array of ids.
     *
     */
    public function addUsers(Activity $activity, $groupId)
    {
        $users = $activity->users->whereIn('id', $this->request->input('users'));

        // get ids of the pivot table records for the users
        $pivotsToUpdate = [];
        foreach($users as $user) {
            array_push($pivotsToUpdate, 
                $user->pivot->id
            );
        }

        // update the pivot records
        DB::table('activity_user')->whereIn('id', $pivotsToUpdate)->update([
            'group_id' => $groupId
        ]);

        return 'success';
    }

    /**
     * Add a number of groups as specified by numberOfGroups with groupPrefix.
     */
    public function batch(Activity $activity)
    {
        $prefix = $this->request->input('groupPrefix');
        $numberToCreate = $this->request->input('numberToCreate');

        $groupsToInsert = [];

        if (!empty($prefix)) {
            for ($i = 0; $i < $numberToCreate; $i++) {
                array_push($groupsToInsert, [
                    'activity_id' => $activity->id,
                    'name' => $prefix . ' ' . ($i + 1),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            DB::table('groups')->insert($groupsToInsert);
            // reload activity groups for use in view composer
            $activity->load('groups');
        }

        $groupsArray = $activity->getGroupListArray();

        return $groupsArray;
    }

    /**
     * Add a bunch of groups passed in the request as a text string.
     */
    public function bulk(Activity $activity)
    {
        $groupText = $this->request->input('groups');
        $groupNames = collect(preg_split('/\r\n|[\r\n]/', $groupText));

        $groupsToInsert = [];

        if ($groupNames->count() > 0) {
            foreach($groupNames as $groupName) {
                if (!empty($groupName)) {
                    array_push($groupsToInsert, [
                        'activity_id' => $activity->id,
                        'name' => $groupName,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            DB::table('groups')->insert($groupsToInsert);
            // reload activity groups for use in view composer
            $activity->load('groups');       
        }

        $groupsArray = $activity->getGroupListArray();

        return $groupsArray;
    }

    /**
     * Delete the group with the given ID, set any members of activity with
     * that group ID's group to null.
     */
    public function delete(Activity $activity, $groupId)
    {
        DB::table('activity_user')->where('activity_id', $activity->id)->where('group_id', $groupId)->update(['group_id' => null]);
        $group = $activity->groups()->where('id', $groupId)->first();
        $group->delete();

        return 'success';
    }

    /**
     * Display the form for managing groups.
     */
    public function index(Activity $activity)
    {
        $groupsArray = $activity->getGroupListArray();
 
        return view('staff.groups')
             ->with('groups', $groupsArray);
    }

    /**
     * Update the group name.
     */
    public function update(Activity $activity, $groupId)
    {
        $group = $activity->groups->where('id', $groupId)->first();
        $group->name = $this->request->input('groupName');
        $group->save();

        return $group->name;
    }

}
