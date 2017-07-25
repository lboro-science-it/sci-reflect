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
            $ratings = $user->ratings->where('round_id', $round->id)->sortByDesc('rating');

            $skills = collect(array());
            $roundSkills = $round->getSkills();
            $max = $this->reflect->getChoices()->max('value');

            foreach($ratings as $rating) {
                $skill = $roundSkills->where('id', $rating->skill_id)->first();
                $skill->rating = $rating->rating;
                $skill->max = $max;
                $skill->percent = $skill->rating / $skill->max * 100;

                if ($skill->percent > 80) {
                    $skill->background = '#ffcf36';
                } elseif ($skill->percent > 50) {
                    $skill->background = '#f4a300';
                } elseif ($skill->percent > 25) {
                    $skill->background = '#ef7d00';
                } else {
                    $skill->background = '#ed6000';
                }

                $skills->push($skill);
            }

            return $skills;
        }

        return null;
    }

}