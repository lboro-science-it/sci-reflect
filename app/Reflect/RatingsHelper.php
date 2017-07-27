<?php

namespace App\Reflect;

use App\Reflect\Reflect;
use DB;
use Illuminate\Http\Request;
use stdClass;

class RatingsHelper
{
    protected $reflect;
    protected $request;

    public function __construct(Request $request, Reflect $reflect)
    {
        $this->reflect = $reflect;
        $this->request = $request;
    }

    private function getSkillValues($selections, $round)
    {
        $choices = $this->reflect->getChoices();    // required for value
        $indicators = $round->getIndicators();      // required for skillId
        
        // build an array of average selectons for each skill
        $skills = array();
        foreach($selections as $selection) {
            $skillId = $indicators->where('id', $selection->indicator_id)->first()->skill_id;
            
            // build an array tracking totals for each skill
            if (!array_key_exists($skillId, $skills)) {
                $skill = new stdClass();
                $skill->id = $skillId;
                $skill->totalIndicators = 0;
                $skill->totalValue = 0;
                $skill->averageValue = 0;

                $skills[$skillId] = $skill;
            }

            $skills[$skillId]->totalIndicators++;
            $skills[$skillId]->totalValue += $choices->where('id', $selection->choice_id)->first()->value;
            $skills[$skillId]->averageValue = floor($skills[$skillId]->totalValue / $skills[$skillId]->totalIndicators);
        }

        return $skills;
    }

    /**
     * Creates ratings in given $round for $user based on user's selections
     *
     * @return decimal
     */
    public function createRatings($round, $user)
    {
        $selections = $user->selections->where('round_id', $round->id);
        $skillValues = $this->getSkillValues($selections, $round);

        $ratings = collect(array());
        foreach ($skillValues as $skill) {
            $rating = new \App\Rating;
            $rating->rated_id = $user->id;
            $rating->rater_id = $user->id;
            $rating->round_id = $round->id;
            $rating->skill_id = $skill->id;
            $rating->rating = $skill->averageValue;
            $rating->created_at = date('Y-m-d H:i:s');
            $rating->updated_at = date('Y-m-d H:i:s');

            $ratings->push($rating);
        }

        // save updated ratings in user model without having to select them again
        $user->setRelation('ratings', $ratings->merge($user->ratings));

        DB::table('ratings')->insert($ratings->toArray());
        return $user->ratings->where('round_id', $round->id);
    }
}