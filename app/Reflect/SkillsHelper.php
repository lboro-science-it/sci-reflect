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

    /**
     * Returns a collection of $skills for the given $user in the given $round
     * based only on the skills in the $round.
     * todo: base skills on activity's skills, not rounds
     *
     * @return View
     */
    public function getSkills($round, $user)
    {
        if (isset($round)) {
            $ratings = $this->getUserRatings($round, $user)
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
            $ratings = $this->getUserRatings($round, $user)
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
            $ratings = $this->getUserRatings($round, $user)
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

    private function getUserRatings($round, $user)
    {
        if (isset($round)) {
            return $user->ratings->where('round_id', $round->id);
        }
    }

}