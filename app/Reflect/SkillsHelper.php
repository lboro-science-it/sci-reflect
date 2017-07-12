<?php

namespace App\Reflect;

use stdClass;

class SkillsHelper
{
    protected $round;

    protected $user;

    public function __construct($round, $user)
    {
        $this->round = $round;
        $this->user = $user;

        $this->reflect = app('Reflect');
    }

    public function getStrongestSkills()
    {
        if (isset($this->round)) {
            $ratings = $this->user->ratings->where('round_id', $this->round->id)
                                           ->sortByDesc('rating')->splice(0, 3);
            
            $strongestSkills = collect(array());
            $roundSkills = $this->round->getSkills();

            foreach ($ratings as $rating) {
                $skill = $roundSkills->where('id', $rating->skill_id)->first();
                $skill->rating = $rating->rating;
                $skill->max = $this->reflect->getChoices()->max('value');
                $strongestSkills->push($skill);
            }

            return $strongestSkills;
        }

        return null;
    }

    public function getWeakestSkills()
    {
        if (isset($this->round)) {
            $ratings = $this->user->ratings->where('round_id', $this->round->id)
                                           ->sortBy('rating')->splice(0, 3);
            
            $weakestSkills = collect(array());
            $roundSkills = $this->round->getSkills();

            foreach ($ratings as $rating) {
                $skill = $roundSkills->where('id', $rating->skill_id)->first();
                $skill->rating = $rating->rating;
                $skill->max = $this->reflect->getChoices()->max('value');
                $weakestSkills->push($skill);
            }

            return $weakestSkills;
        }

        return null;
    }

}