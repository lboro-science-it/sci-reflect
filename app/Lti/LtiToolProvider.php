<?php

namespace App\Lti;

use App\Activity;
use App\User;
use Auth;
use IMSGlobal\LTI\ToolProvider\ToolProvider;

class LtiToolProvider extends ToolProvider
{
    function onLaunch() 
    {
        // find or create the user based on launcher email
        $user = User::firstOrCreate([
            'email' => $this->user->email,
            'name' => $this->user->fullname
        ]);
        $this->user_id = $user->id;

        // find or create an activity record based on launch
        $activity = Activity::firstOrCreate([
            'resource_link_record_id' => $this->resourceLink->getRecordId(),
            'consumer_pk' => $this->consumer->getRecordId(),
        ]);
        if ($activity->wasRecentlyCreated) {
            $activity->name = $this->resourceLink->title . ' (' . $this->context.title . ')';
            $activity->save();
        }
        $this->activity_id = $activity->id;

        // set user role in the pivot table
        $role = $this->user->isStaff() ? 'staff' : 'student';
        if (!$user->activities()->where('activity_id', '=', $this->activity_id)->count()) {
            $user->activities()->attach($activity, [
                'role' => $role,
                'lti_user_id' => $this->user->ltiUserId
            ]);
        } else {
            $user->activities()->updateExistingPivot($this->activity_id, [
                'role' => $role
            ]);
        }
    }

    function onError() 
    {
        $this->ok = false;
        return true;
    }
}