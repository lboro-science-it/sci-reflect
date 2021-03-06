<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Rating;
use App\Round;
use App\User;
use Auth;
use Illuminate\Http\Request;

class StudentChartController extends Controller
{
    /**
     * Eager load necessary data then render the single chart view.
     * Necessary data includes the data for the chart, the skills list, 
     * and the completed rounds.
     *
     * @param  App\Activity $activity
     * @param  App\Round $round
     * @return View
     */    
    public function show(Activity $activity, Round $round, $raterId = null)
    {
        $activity->rounds->load([
            'pages.skills.indicators',
            'pages.skills.block'
        ]);

        // get the round object with the eager loaded data
        $round = $activity->rounds->where('id', $round->id)->first();

        // get either the user's own ratings, or rater's if set in the route
        $raterId = $raterId ?? Auth::user()->id;

        $rater = ($raterId == Auth::user()->id) ? Auth::user() : User::find($raterId);

        $ratings = Rating::where('round_id', $round->id)
                         ->where('rated_id', Auth::user()->id)
                         ->where('rater_id', $rater->id)
                         ->get();

        $chartData = $activity->getChartDataFromRatings($ratings);
        $categories = $activity->getCategories();
        $skills = $activity->getSkillsFromRatings($ratings);

        return view('chart.single')
        ->with('chartData', $chartData)
        ->with('categories', $categories)
        ->with('skills', $skills)
        ->with('rater', $rater)
        ->with('round', $round)
        ->with('rounds', $activity->getRoundsData());
    }

}
