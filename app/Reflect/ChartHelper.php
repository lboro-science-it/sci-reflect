<?php

namespace App\Reflect;

use DB;
use stdClass;

class ChartHelper
{
    protected $round;

    protected $user;

    protected $ratings;

    public function __construct($round, $user)
    {
        $this->reflect = app('Reflect');
        $this->activity = request()->route('activity');

        $this->round = $round;
        $this->user = $user;
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
    public function getChartData()
    {
        $chartData = new stdClass();

        $this->ratings = $this->getRatings();
        $this->skills = $this->getSkillsFromIds($this->ratings->pluck('skill_id'));
        $this->categories = $this->skills->pluck('category');        
        
        $chartData->backgrounds = $this->categories->pluck('color');
        $chartData->labels = $this->skills->pluck('title');
        $chartData->values = $this->ratings->pluck('rating');
        
        $chartData->max = $this->reflect->getChoices()->max('value');

        return $chartData;
    }

    /**
     * Returns $this->user's ratings in $this->round, getting them if they
     * already exist, or calculating and inserting them first if not.
     * @return collection
     */
    // todo: order ratings by related skill's number
    private function getRatings()
    {
        $ratings = $this->user->ratings->where('round_id', $this->round->id);
        
        if (!$ratings->count()) {
            $ratings = $this->createRatings();
        }

        return $ratings;
    }

    /**
     * Returns collection of Skills with categories from an array of IDs.
     * @return collection
     */
    private function getSkillsFromIds($skillIds)
    {
        $skills = collect(array());

        $categories = $this->activity->categories;

        $roundSkills = $this->round->getSkills();

        foreach ($skillIds as $skillId) {
            $skill = $roundSkills->where('id', $skillId)->first();
            $skill->setRelation('category', $categories->where('id', $skill->category_id)->first());

            $skills->push($roundSkills->where('id', $skillId)->first());
        }

        return $skills;
    }

}