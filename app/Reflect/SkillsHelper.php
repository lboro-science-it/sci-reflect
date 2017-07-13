<?php

namespace App\Reflect;

use App\Reflect\Reflect;
use stdClass;

class SkillsHelper
{
    protected $reflect;

    public function __construct(Reflect $reflect)
    {
        $this->reflect = $reflect;
    }

    public function getSkills($round, $user)
    {
        if (isset($round)) {
            $ratings = $user->ratings->where('round_id', $round->id)
                                           ->sortByDesc('rating');

            $skills = collect(array());
            $roundSkills = $round->getSkills();
            $max = $this->reflect->getChoices()->max('value');

            foreach($ratings as $rating) {
                $skill = $roundSkills->where('id', $rating->skill_id)->first();
                $skill->rating = $rating->rating;
                $skill->max = $max;
                $skills->push($skill);
            }

            return $skills;
        }

        return null;
    }

    public function getStrongestSkills($round, $user)
    {
        if (isset($round)) {
            $ratings = $user->ratings->where('round_id', $round->id)
                                           ->sortByDesc('rating')->splice(0, 3);
            
            $strongestSkills = collect(array());
            $roundSkills = $round->getSkills();
            $max = $this->reflect->getChoices()->max('value');

            foreach ($ratings as $rating) {
                $skill = $roundSkills->where('id', $rating->skill_id)->first();
                $skill->rating = $rating->rating;
                $skill->max = $max;
                $strongestSkills->push($skill);
            }

            return $strongestSkills;
        }

        return null;
    }

    public function getWeakestSkills($round, $user)
    {
        if (isset($round)) {
            $ratings = $user->ratings->where('round_id', $round->id)
                                           ->sortBy('rating')->splice(0, 3);
            
            $weakestSkills = collect(array());
            $roundSkills = $round->getSkills();
            $max = $this->reflect->getChoices()->max('value');

            foreach ($ratings as $rating) {
                $skill = $roundSkills->where('id', $rating->skill_id)->first();
                $skill->rating = $rating->rating;
                $skill->max = $max;
                $weakestSkills->push($skill);
            }

            return $weakestSkills;
        }

        return null;
    }

}