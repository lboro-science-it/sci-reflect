<?php

namespace App\Lti;

use App\Activity;
use App\User;
use Auth;
use IMSGlobal\LTI\ToolProvider\ToolProvider;

class LtiToolProvider extends ToolProvider
{
    /**
     * Identifies our User and Activity models based on the launching data,
     * creating them if necessary, and setting pivot data.
     * These can then be accessed from controller via $tool->user_id and
     * $tool->activity_id. User is NOT authed here.
     *
     * @return void
     */
    function onLaunch() 
    {
        // find or create the user based on launcher email
        $user = User::firstOrCreate([
            'email' => $this->user->email
        ]);
        $user->name = $this->user->fullname;
        $user->save();
        $this->user_id = $user->id;

        // find or create an activity record based on launch
        $activity = Activity::firstOrCreate([
            'resource_link_record_id' => $this->resourceLink->getRecordId(),
            'consumer_pk' => $this->consumer->getRecordId()
        ]);
        if ($activity->wasRecentlyCreated) {
            $activity->name = $this->resourceLink->title . ' (' . $this->context->title . ')';
            $activity->save();
        }
        $this->activity_id = $activity->id;

        // set user role in the pivot table
        $role = $this->user->isStaff() ? 'staff' : 'student';
        if (!$user->activities()->where('activity_id', '=', $this->activity_id)->count()) {
            $user->activities()->attach($activity, [
                'role' => $role,
                'lti_user_id' => $this->user->ltiUserId,
                'current_page' => $role == 'staff' ? null : 1,
                'current_round' => $role == 'staff' ? null : 1
            ]);
        } else {
            $user->activities()->updateExistingPivot($this->activity_id, [
                'role' => $role
            ]);
        }
    }

    /**
     * Manually handle an error following $tool->handleRequest().
     * Setting $this->ok to false can be tested in the controller, 
     * if (!$tool->ok). We could also add message, etc.
     * return true overrides the default onError behaviour of parent class.
     *
     * @return bool
     */
    function onError() 
    {
        $this->ok = false;
        return true;
    }
}