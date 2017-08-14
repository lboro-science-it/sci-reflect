<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Round;
use App\User;
use Auth;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /** 
     * Show the form for rating a student with given $studentId. Staff
     * middleware prevents this being accessible unless Auth::user has role
     * 'staff' in $activity.
     * todo: also account for groups
     *
     */
    public function show(Activity $activity, Round $round, $studentId, Request $request)
    {
        $skills = $round->getSkills();
        $choices = app('Reflect')->getChoices();
        $ratings = Auth::user()->ratings()->where('rated_id', $studentId)->where('round_id', $round->id)->get();
        $student = User::find($studentId);

        return view('rating.single')
             ->with('skills', $skills)
             ->with('choices', $choices)
             ->with('ratings', $ratings)
             ->with('round', $round)
             ->with('student', $student);
    }
}
