<?php

namespace App\Reflect;

use DB;
use stdClass;

class ChartHelper
{
    protected $round;

    protected $user;

    public function __construct()
    {
        $this->reflect = app('Reflect');
    }

    /**
     * Returns an array of skills with averageValues for $this->user's
     * selections in $this->round.
     * @return array
     */
    private function getAverageSkillValues()
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
        $averageSkillValues = $this->getAverageSkillValues();

        $ratingsToInsert = array();
        foreach ($averageSkillValues as $skill) {
            $rating = array(
                'user_id' => $this->user->id,
                'rater_id' => $this->user->id,
                'round_id' => $this->round->id,
                'skill_id' => $skill->id,
                'rating' => $skill->averageValue,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            array_push($ratingsToInsert, $rating);
        }

        DB::table('ratings')->insert($ratingsToInsert);
        return $this->user->ratings()->where('round_id', $this->round->id)->get();
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

        $ratings = $this->getRatings();
        $ratingsArray = $ratings->toArray();

        $chartData->values = array_column($ratingsArray, 'rating');

        $skillIds = array_column($ratingsArray, 'skill_id');
        $skills = $this->getSkillsFromIds($skillIds);

        $chartData->labels = array_column($skills->toArray(), 'title');

        $chartData->max = $this->reflect->getChoices()->max('value');

        $categories = array_column($skills->toArray(), 'category');
        $backgrounds = array_column($categories, 'color');

        $chartData->backgrounds = $backgrounds;
        // so for each skill we need to find that skill's category so we can get the color, name, icon (?)

        return $chartData;
    }

    /**
     * Returns $this->user's ratings in $this->round, getting them if they
     * already exist, or calculating and inserting them first if not.
     * @return collection
     */
    private function getRatings()
    {
        $ratings = $this->user->ratings()->where('round_id', $this->round->id)->get();
        
        if (!$ratings->count()) {
            $ratings = $this->createRatings();
        }

        return $ratings;
    }

    private function getSkillsFromIds($skillIds)
    {
        $skills = collect(array());

        $categories = request()->route('activity')->categories;

        $roundSkills = $this->round->getSkills();

        foreach ($skillIds as $skillId) {
            $skill = $roundSkills->where('id', $skillId)->first();
            $skill->setRelation('category', $categories->where('id', $skill->category_id)->first());

            $skills->push($roundSkills->where('id', $skillId)->first());
        }

        return $skills;
    }

}