<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\SkillsHelper;
use App\Rating;
use App\Round;
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

        // get the ratings for the round ready to pass to the activity method
        $raterId = is_null($raterId) ? Auth::user()->id : $raterId;
        $ratings = Rating::where('round_id', $round->id)
                         ->where('rated_id', Auth::user()->id)
                         ->where('rater_id', $raterId)
                         ->get();

        $chartData = $activity->getChartDataFromRatings($ratings);


        $skillsHelper = app('SkillsHelper');

        $categories = $activity->getCategories();
        $skills = $skillsHelper->getActivitySkills($round, Auth::user());


        return view('chart.single')
        ->with('chartData', $chartData)
        ->with('categories', $categories)
        ->with('skills', $skills)
        ->with('round', $round)
        ->with('rounds', $activity->getRoundsData());
    }

}
