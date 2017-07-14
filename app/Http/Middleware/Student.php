<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Student
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
        if (!Auth::check()) {
            return redirect('eject');
        }

        if (Auth::user()->role !== 'student') {
            return redirect('activity/' . $request->route('activity')->id);
        }
        
        return $next($request);
    }
}
