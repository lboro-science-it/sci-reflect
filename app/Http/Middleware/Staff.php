<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Staff
{
    /**
     * Redirect the user to the eject controller method if they are not
     * staff in the current activity.
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

        // session contains array of user's launched activities w/ roles
        $userActivities = $request->session()->get('activities');

        if ($userActivities[$activityId]['role'] !== 'staff') {
            return redirect('eject');
        }

        return $next($request);
    }
}
