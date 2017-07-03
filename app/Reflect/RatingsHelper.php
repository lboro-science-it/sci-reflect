<?php

namespace App\Reflect;

use DB;
use stdClass;

class RatingsHelper
{

    /**
     * Returns an array of skills with averageValues for $this->user's
     * selections in $this->round.
     * @return array
     */
    private function getAverageSkillValues()
    {
        $selections = $this->user->selections()->where('round_id', '=', $this->round->id)->with('indicator')->with('choice')->get();

        $skills = array();
        foreach($selections as $selection) {
            $id = $selection->indicator->skill_id;

            if (!array_key_exists($id, $skills)) {
                $skill = new stdClass();
                $skill->id = $id;
                $skill->totalIndicators = 0;
                $skill->totalValue = 0;
                $skill->averageValue = 0;

                $skills[$id] = $skill;
            }

            $skills[$id]->totalIndicators++;
            $skills[$id]->totalValue += $selection->choice->value;
            $skills[$id]->averageValue = $skills[$id]->totalValue / $skills[$id]->totalIndicators;
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
        return $this->queryRatings();
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

        $skills = array_column($ratingsArray, 'skill');
        $chartData->labels = array_column($skills, 'title');

        return $chartData;
    }

    /**
     * Returns $this->user's ratings in $this->round, getting them if they
     * already exist, or calculating and inserting them first if not.
     * @return collection
     */
    private function getRatings()
    {
        $ratings = $this->queryRatings();
        
        if (!$ratings->count()) {
            $ratings = $this->createRatings();
        }

        return $ratings;
    }

    /**
     * Queries $this->user's ratings in $this->round
     * @return collection
     */
    private function queryRatings()
    {
        return $this->user->ratings()->where('round_id', '=', $this->round->id)
        ->with('skill')
        ->get();
    }
}