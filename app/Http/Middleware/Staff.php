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
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect('eject');
        }

        return $next($request);
    }
}
