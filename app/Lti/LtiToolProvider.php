<?php

namespace App\Lti;

use App\Activity;
use App\User;
use Auth;
use IMSGlobal\LTI\ToolProvider\ToolProvider;

class LtiToolProvider extends ToolProvider
{

    private function createOrUpdatePivot($user, $activity, $role)
    {
        $userActivity = $user->activities->where('id', $this->activity_id)->first();

        if (is_null($userActivity)) {
            // create the relationship
            $user->activities()->attach($activity, [
                'role' => $role,
                'lti_user_id' => $this->user->ltiUserId,
                'current_page' => 1,
                'current_round' => 1
            ]);
        } else {
            // just need to update the pivot
            $user->activities()->updateExistingPivot($this->activity_id, [
                'role' => $role,
                'lti_user_id' => $this->user->ltiUserId
            ]);
        }
    }

    private function getCurrentRoundNumber($user)
    {
        $userActivity = $user->activities->where('id', $this->activity_id)->first();
        if (is_null($userActivity)) {
            return 1;
        } else {
            return $userActivity->pivot->current_round;
        }
    }

    private function getOrCreateActivity()
    {
        // find or create an activity record based on launch
        $activity = Activity::firstOrCreate([
            'resource_link_record_id' => $this->resourceLink->getRecordId(),
            'consumer_pk' => $this->consumer->getRecordId()
        ]);
        if ($activity->wasRecentlyCreated) {
            $activity->name = $this->resourceLink->title . ' (' . $this->context->title . ')';
            $activity->save();
        }

        return $activity;
    }

    private function getOrCreateUser()
    {
        // find or create the user based on launcher email
        $user = User::firstOrCreate([
            'email' => $this->user->email
        ]);
        $user->name = $this->user->fullname;
        $user->save();

        return $user;
    }

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
        $user = $this->getOrCreateUser();
        $this->user_id = $user->id;

        $activity = $this->getOrCreateActivity();
        $this->activity_id = $activity->id;

        // get user role for pivot table
        $role = $this->user->isStaff() ? 'staff' : 'student';
        $this->role = $role;

        $this->createOrUpdatePivot($user, $activity, $role);

        $currentRoundNumber = $this->getCurrentRoundNumber($user);
        $currentRound = $activity->rounds->where('round_number', $currentRoundNumber)->first();

        $this->format = isset($currentRound->format) ? $currentRound->format : $activity->format;
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