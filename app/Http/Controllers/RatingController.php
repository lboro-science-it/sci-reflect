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
        $skills = $round->getSkills()->sortBy('pivot.position');
        $choices = app('Reflect')->getChoices();

        // create array merging existing ratings with skills for this round
        $skillsArray = [];
        $ratings = Auth::user()->ratings()->where('rated_id', $studentId)->where('round_id', $round->id)->get();
        foreach ($skills as $skill) {
            if (!is_null($ratings->where('skill_id', $skill->id)->first())) {
                $skill->rating = $rating->rating;
            } else {
                $skill->rating = null;
            }
            array_push($skillsArray, [
                'id' => $skill->id,
                'title' => $skill->title,
                'description' => $skill->description,
                'rating' => $skill->rating
            ]);
        }

        $student = User::find($studentId);

        return view('rating.single')
             ->with('skills', $skillsArray)
             ->with('choices', $choices)
             ->with('round', $round)
             ->with('student', $student);
    }

    /** 
     * Store ratings for an array of skills, where rated = $studentId and
     * rater = Auth::user()->id
     *
     */
    public function store(Activity $activity, Round $round, $studentId, Request $request)
    {
        $rater_id = Auth::user()->id;
        $rated_id = $studentId;
        $round_id = $round->id;

        return $request->input('skills');

        $ratingsToInsert = [];
        // foreach request->skills as skill
        // skill_id = skill->id
        // rating = skill->rating
        // created_at = date('Y-m-d H:i:s')
        // updated_at = date('Y-m-d H:i:s')
        // array push all the above
        // DB::insert all the above

        return 'success';

    }
}
