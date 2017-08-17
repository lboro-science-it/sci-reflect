<?php

namespace App\Http\Middleware;

use Closure;

class ActivityOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $activity = $request->route('activity');

        if (!$activity->isOpen()) {
            return redirect('a/' . $activity->id . '/closed');
        }

        return $next($request);
    }
}
