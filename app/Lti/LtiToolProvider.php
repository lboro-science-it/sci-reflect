<?php

namespace App\Lti;

use App\Activity;
use App\User;
use Auth;
use IMSGlobal\LTI\ToolProvider\ToolProvider;

class LtiToolProvider extends ToolProvider
{

    private function authOrCreate($launcherEmail, $launcherFullName)
    {
        $existingUser = User::where('email', '=', $launcherEmail)->first();

        if (!empty($existingUser)) {       // user exists, attempt login
            return Auth::loginUsingId($existingUser->id);
        } else {                        // user doesn't exist, create
            $newUser = User::create([
                'name' => $launcherFullName,
                'email' => $launcherEmail,
            ]);
            return Auth::login($newUser);   // return true if login successful
        }
    }

    private function authRequired($launcherEmail) 
    {
        if (!Auth::check()) {
            return true;            // no authed user so auth required
        } else if (Auth::user()->email != $launcherEmail) {
            Auth::logout();
            return true;            // launching user != authed user
        }
        return false;               // launching user == authed user
    }

    private function getOrCreateActivity($resourceLinkRecordId, $consumerPk, $activityTitle)
    {
        $activity = Activity::firstOrCreate(['resource_link_record_id' => $resourceLinkRecordId]);
        if ($activity->wasRecentlyCreated) {
            $activity->resource_link_record_id = $resourceLinkRecordId;
            $activity->consumer_pk = $consumerPk;
            $activity->name = $activityTitle;
            $activity->save();
        }
        return $activity;
    }

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


/*
        // create/auth if launching user isn't already authed (Laravel)
        if ($this->authRequired($this->user->email)) {
            $this->authOrCreate($this->user->email, $this->user->fullname);
        }
*/

/*
        $activityTitle = $this->resourceLink->title . ' (' . $this->context->title . ')';
        $activity = $this->getOrCreateActivity($this->resourceLink->getRecordId(), $this->consumer->getRecordId(), $activityTitle);
*/
//        $this->activity_id = $activity->id;
/*
        $role = 'student';
        if ($this->user->isStaff()) {
            $role = 'staff';
        }
*/
/*
        $ltiUser = User::find(Auth::user()->id);
        // relate user to activity if necessary
        if (!$ltiUser->activities()->where('activity_id', '=', $activity->id)->count()) {
            $ltiUser->activities()->attach($activity, [
                'role' => $role, 
                'lti_user_id' => $this->user->ltiUserId,
            ]);
        } else {            // update role to match user's current role (so they can test as student)
            $ltiUser->activities()->updateExistingPivot($activity->id, ['role' => $role]);
        }
*/
    }

    function onError() 
    {
        $this->ok = false;
        return true;
    }
}