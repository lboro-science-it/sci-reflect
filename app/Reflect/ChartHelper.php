<?php

namespace App\Reflect;

use App\Reflect\Reflect;
use DB;
use Illuminate\Http\Request;
use stdClass;

class ChartHelper
{
    protected $reflect;

    public function __construct(Request $request, Reflect $reflect)
    {
        $this->reflect = $reflect;
        $this->activity = $request->route('activity');
    }

    /**
     * Returns an object of arrays suitable for use in a chartJS options obj.
     *
     * @return Array $chartData
     */
    public function getChartData($round = null, $user = null)
    {
        // get all the activity's skills in correct order
        $skills = $this->activity->getSkills();
        $categories = $this->activity->getCategories();

        // get the user's ratings in this round (so some skills won't have a rating maybe)
        $ratings = isset($user) ? $user->getRatings($round) : collect(array());

        $chartData = new stdClass();
        $chartData->values = [];
        $chartData->backgrounds = [];
        $chartData->borders = [];
        $chartData->labels = [];
        $chartData->enabled = [];
        $chartData->max = $this->reflect->getChoices()->max('value');

        foreach ($skills as $skill) {
            $rating = $ratings->where('skill_id', $skill->id)->first();

            $category = $categories->where('id', $skill->category_id)->first();

            array_push($chartData->labels, $skill->title);
            if (isset($rating)) {       // insert user rating data
                array_push($chartData->values, $rating->rating);
                array_push($chartData->backgrounds, $category->color);
                array_push($chartData->borders, $category->color);
                array_push($chartData->enabled, true);
            } else {                    // insert placeholder data
                array_push($chartData->values, 1);
                array_push($chartData->backgrounds, '#e5e5e5');
                array_push($chartData->borders, '#e5e5e5');
                array_push($chartData->enabled, false);
            }
        }
        
        return $chartData;
    }

}