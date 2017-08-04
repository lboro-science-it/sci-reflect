<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Group;
use Auth;
use DB;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Add a number of groups as specified by numberOfGroups with groupPrefix.
     */
    public function batch(Activity $activity, Request $request)
    {
        $prefix = $request->input('groupPrefix');
        $numberToCreate = $request->input('numberToCreate');

        $groupsToInsert = [];

        if (!empty($prefix)) {
            $totalGroups = $activity->groups->count();
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

        $message = 'Added ' . count($groupsToInsert) . ' groups';

        return view('groups.index')
             ->with('message', $message);
    }

    /**
     * Add a bunch of groups passed in the request as a text string.
     */
    public function bulk(Activity $activity, Request $request)
    {
        $groupText = $request->input('groups');
        $groupNames = collect(preg_split('/\r\n|[\r\n]/', $groupText));

        $groupsToInsert = [];

        if ($groupNames->count() > 0) {
            $groupNumber = $activity->groups->count();
            foreach($groupNames as $groupName) {
                if (!empty($groupName)) {
                    $groupNumber++;
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

        $message = 'Added ' . count($groupsToInsert) . ' groups';

        return view('groups.index')
             ->with('message', $message);
    }

    /**
     * Display the form for managing groups.
     */
    public function index(Request $request, Activity $activity)
    {
        if ($request->ajax()) {
            $groups = $activity->groups->sortBy('name');
            $groups->load('activityUsers.user');
            foreach($groups as $group) {
                $group->userCount = $group->getUsers()->count();
            }

            return $groups;
        }


        $groups = $activity->groups->sortBy('name');
        $groups->load('activityUsers.user');
        foreach($groups as $group) {
            $group->userCount = $group->getUsers()->count();
        }

        return view('groups.index')
             ->with('groups', $groups);
    }

    // todo: decide on routes for server rendered partials and routes for API-ish AJAX requests.

}
