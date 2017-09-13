<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Rating;
use App\Round;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Show the review of ratings for a student with given $studentId.
     * Staff middleware prevents access unless Auth::user() is 'staff'.
     * Note this is basically the same as the show chart method for students,
     * if I wasn't leaving I would take a bit more time and tidy up the code
     * as it's pretty much a copy and paste. What we need is to take the below
     * code and stick it as a method on something like $round, e.g. so we call
     * $round->getRatings($ratedId, $raterId) or whatever.
     */
    public function review(Activity $activity, Round $round, $studentId)
    {
        // todo: test the student belongs to the activity (low priority)

        // this code is same as what is used in $activity->getRoundsData, to get
        // the user id of the rater of the student so we can get the ratings.
        // get the first unique staff rating, so we can display a link to it
        $staffRating = $round->ratings->filter(function ($item) use ($studentId) {
            return $item['rater_id'] != $studentId;
        })->unique('rater_id')->first();

        // store the id of the staff member so we can view their chart
        if (!isset($staffRating)) {
            return redirect('eject');
        }

        // now we can use $staffRating->rater_id to get the rater_id
        $ratings = Rating::where('round_id', $round->id)
                         ->where('rated_id', $studentId)
                         ->where('rater_id', $staffRating->rater_id)
                         ->get();

        $chartData = $activity->getChartDataFromRatings($ratings);
        $categories = $activity->getCategories();
        $skills = $activity->getSkillsFromRatings($ratings);

        $student = User::find($studentId);

        return view('staff.review')
             ->with('categories', $categories)
             ->with('chartData', $chartData)
             ->with('ratings', $ratings)
             ->with('round', $round)
             ->with('skills', $skills)
             ->with('student', $student);
    }

    /** 
     * Show the form for rating a student with given $studentId. Staff
     * middleware prevents this being accessible unless Auth::user has role
     * 'staff' in $activity.
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
            $rating = $ratings->where('skill_id', $skill->id)->first();
            if (!is_null($rating)) {
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

        return view('staff.rating')
             ->with('skills', $skillsArray)
             ->with('choices', $choices)
             ->with('descriptors', $activity->descriptors)
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
        $skillRatings = $request->input('skills');

        // delete any existing records of the same skills/rater/rated
        DB::table('ratings')->where('rater_id', $rater_id)
                            ->where('rated_id', $rated_id)
                            ->where('round_id', $round_id)
                            ->whereIn('skill_id', array_keys($skillRatings))
                            ->delete();

        // prepare array of records to be inserted based on input
        $ratingsToInsert = [];
        foreach ($skillRatings as $skill_id => $rating) {
            if (!is_null($rating)) {
                array_push($ratingsToInsert, [
                    'rater_id' => $rater_id,
                    'rated_id' => $rated_id,
                    'round_id' => $round_id,
                    'skill_id' => $skill_id,
                    'rating' => $rating,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        DB::table('ratings')->insert($ratingsToInsert);

        return 'success';
    }
}
