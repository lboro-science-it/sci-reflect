<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Student
{
    /**
     * Log out the user if they aren't authed, redirect to the dashboard
     * if they are not a student in the activity.
     * todo: look again at this, as it would probably cause a redirect loop
     * - only if a user is trying to look at a student route for an activity
     * they aren't a member of, so not too urgent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('eject');
        }

        $activityId = $request->route('activity')->id;
        $userActivities = $request->session()->get('activities');

        if ($userActivities[$activityId]['role'] !== 'student') {
            return redirect('a/' . $activityId);
        }

        return $next($request);
    }
}
