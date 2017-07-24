<?php

namespace App\Reflect;

use App\Reflect\Reflect;
use DB;
use Illuminate\Http\Request;
use stdClass;

class ChartHelper
{
    protected $reflect;
    protected $round;
    protected $user;

    public function __construct(Request $request, Reflect $reflect)
    {
        $this->reflect = $reflect;
        $this->activity = $request->route('activity');
    }

    /**
     * Calculates averages for each of $this->user's skills in $this->round,
     * adds them to database and returns the resulting collection.
     * @return collection
     */
    private function createRatings()
    {
        $averageSkillValues = $this->getSkillValues();

        $ratingsToInsert = array();
        $ratingsCollection = collect(array());
        foreach ($averageSkillValues as $skill) {
            $rating = new \App\Rating;
            $rating->user_id = $this->user->id;
            $rating->rater_id = $this->user->id;
            $rating->round_id = $this->round->id;
            $rating->skill_id = $skill->id;
            $rating->rating = $skill->averageValue;
            $rating->created_at = date('Y-m-d H:i:s');
            $rating->updated_at = date('Y-m-d H:i:s');

            array_push($ratingsToInsert, $rating->toArray());

            $ratingsCollection->push($rating);
        }

        // save updated ratings in user model without having to select them again
        $this->user->setRelation('ratings', $ratingsCollection->merge($this->user->ratings));

        DB::table('ratings')->insert($ratingsToInsert);
        return $this->user->ratings->where('round_id', $this->round->id);
    }

    /**
     * Returns Ratings data formatted for rendering in a chart view.
     * @return obj $chartData
     */
    public function getChartData($round, $user)
    {
        $this->round = $round;
        $this->user = $user;

        $chartData = new stdClass();

        $skills = $this->getSkills();

        $chartData->backgrounds = $skills->pluck('category')->pluck('color');
        $chartData->borders = $skills->pluck('category')->pluck('color');
        $chartData->labels = $skills->pluck('title');
        $chartData->values = $this->getRatings($skills);

        $chartData->max = $this->reflect->getChoices()->max('value');

        $chartData = $this->styleZeroes($chartData);

        return $chartData;
    }

    /**
     * Returns data for rendering a placeholder chart with no ratings.
     * todo: obviously this whole class needs a refactor now.
     * @return obj $chartData
     */
    public function getPlaceholderData($round)
    {
        $this->round = $round;

        $chartData = new stdClass();

        $skills = $this->getSkills();
        $chartData->backgrounds = $skills->pluck('category')->pluck('color');
        $chartData->borders = $skills->pluck('category')->pluck('color');
        $chartData->labels = $skills->pluck('title');

        $chartData->values = array();

        for ($i = 0; $i < $skills->count(); $i++) {
            array_push($chartData->values, 0);
        }

        $chartData->max = $this->reflect->getChoices()->max('value');

        $chartData = $this->styleZeroes($chartData);

        return $chartData;
    }

    /**
     * Returns $this->user's ratings in $this->round, getting them if they
     * already exist, or calculating and inserting them first if not.
     * Ratings are mapped against $skills
     * @return collection
     */
    private function getRatings($skills)
    {
        $ratings = $this->user->ratings->where('round_id', $this->round->id);
        
        if (!$ratings->count()) {
            $ratings = $this->createRatings();
        }

        $ratingsArray = array();

        foreach ($skills as $skill) {
            $rating = $ratings->where('skill_id', $skill->id)->first();
            
            $ratingValue = is_null($rating) ? 0 : $rating->rating;

            array_push($ratingsArray, $ratingValue);
        }

        return $ratingsArray;
    }

    private function getSkills()
    {
        $skills = $this->activity->getSkills();

        $categories = $skills->pluck('category')->unique()->sortBy('name')->sortBy('number');
        $sortedSkills = collect(array());
        foreach ($categories as $category) {
            $sortedSkills = $sortedSkills->merge($skills->where('category_id', $category->id)->sortBy('title')->sortBy('number'));
        }
        return $sortedSkills;
    }

    /**
     * Returns an array of skills with average Values for $this->user's
     * selections in $this->round.
     * @return array
     */
    private function getSkillValues()
    {
        $selections = $this->user->selections->where('round_id', $this->round->id);

        $indicators = $this->round->getIndicators();
        $choices = $this->reflect->getChoices();

        $skills = array();
        foreach($selections as $selection) {
            $indicator = $indicators->where('id', $selection->indicator_id)->first();
            $skillId = $indicator->skill_id;
            $choice = $choices->where('id', $selection->choice_id)->first();

            if (!array_key_exists($skillId, $skills)) {
                $skill = new stdClass();
                $skill->id = $skillId;
                $skill->totalIndicators = 0;
                $skill->totalValue = 0;
                $skill->averageValue = 0;

                $skills[$skillId] = $skill;
            }

            $skills[$skillId]->totalIndicators++;
            $skills[$skillId]->totalValue += $choice->value;
            $skills[$skillId]->averageValue = floor($skills[$skillId]->totalValue / $skills[$skillId]->totalIndicators);
        }

        return $skills;
    }

    private function styleZeroes($chartData)
    {
        // overwrites empty values with greyed out full values...
        // not the most elegant solution but otherwise there are empty areas of the chart which can't be 
        // hovered over to reveal what skills are supposed to go there.
        foreach ($chartData->values as $key => $value) {
            if ($value == 0) {
                $disabledColor = '#e5e5e5';
                $chartData->values[$key] = 1;
                $chartData->backgrounds[$key] = $disabledColor;
            }
        }

        return $chartData;
    }

}