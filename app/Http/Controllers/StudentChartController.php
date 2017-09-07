<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Reflect\ChartHelper;
use App\Reflect\SkillsHelper;
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
    public function show(Activity $activity, Round $round, $scope = null)
    {
        $activity->rounds->load([
            'pages.skills.indicators'
        ]);

        // get the round object with the eager loaded data
        $round = $activity->rounds->where('id', $round->id)->first();

        $chartHelper = app('ChartHelper');
        $chartData = $chartHelper->getChartData($round, Auth::user());

        $skillsHelper = app('SkillsHelper');

        // todo: separate categories and skills so in the view categories can be iterated
        // then skills where category (from a separate object)

        $categories = collect();
        $skills = collect();
        if (!isset($scope)) {
            // todo: move skillsHelper stuff to ratingsHelper as it's more about that...
            $categories = $activity->getCategories();
            $skills = $skillsHelper->getActivitySkills($round, Auth::user());
        } elseif ($scope == 'strongest') {
            $skills = $skillsHelper->getUserSkills($round, Auth::user());
        }

        return view('chart.single')
        ->with('chartData', $chartData)
        ->with('categories', $categories)
        ->with('skills', $skills)
        ->with('round', $round)
        ->with('rounds', $activity->getRoundsData());
    }

}
