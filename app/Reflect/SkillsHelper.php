<?php

namespace App\Reflect;

use App\Reflect\Reflect;
use Illuminate\Http\Request;
use stdClass;

class SkillsHelper
{
    protected $activity;

    protected $reflect;

    protected $request;

    public function __construct(Request $request, Reflect $reflect)
    {
        $this->activity = $request->route('activity');
        $this->reflect = $reflect;
        $this->request = $request;
    }

    /**
     * Returns a collection of $skills for the given $user in the given $round
     * based only on the skills in the $round.
     * todo: base skills on activity's skills, not rounds, with ones not in round grey 
     *
     * @return View
     */
    public function getUserSkills($round, $user)
    {
        $skills = collect(array());

        $categories = $this->activity->getCategories();

        if (isset($round)) {
            $ratings = $user->ratings->where('round_id', $round->id)->sortByDesc('rating');

            $roundSkills = $round->getSkills();
            $max = $this->reflect->getChoices()->max('value');

            foreach($ratings as $rating) {
                $skill = $roundSkills->where('id', $rating->skill_id)->first();
                $skill->rating = $rating->rating;
                $skill->max = $max;
                $skill->percent = $skill->rating / $skill->max * 100;
                $skill->background = $this->getBackgroundColor($skill->percent);
                $skill->setRelation('category', $categories->where('id', $skill->category_id)->first());

                $skills->push($skill);
            }
        }

        return $skills;
    }

    /**
     * Returns all $activity skills in render order including user's rating 
     * where available, for given round.
     *
     */
    public function getActivitySkills($round, $user)
    {
        $skills = $this->activity->getSkills();
        $ratings = $user->getRatings($round);
        $max = $this->reflect->getChoices()->max('value');

        foreach($skills as $skill) {
            $rating = $ratings->where('skill_id', $skill->id)->first();
            $skill->max = $max;

            if (isset($rating)) {       // insert user rating data
                $skill->rating = $rating->rating;
                $skill->percent = $skill->rating / $max * 100;
                $skill->background = $this->getBackgroundColor($skill->percent);
            } else {                    // insert placeholder data
                $skill->rating = 0;
                $skill->percent = 0;
                $skill->background = '#e5e5e5';
            }
        }

        return $skills;
    }

    /**
     * Returns $user's skills in a given $round but with all $activity skills also
     *
     */
    public function getActivitySkillsInCategories($round, $user)
    {
        $categories = $this->activity->getSkillsInCategories();

        $ratings = $user->getRatings($round);

        $max = $this->reflect->getChoices()->max('value');

        foreach($categories as $category) {
            foreach($category->skills as $skill) {
                $rating = $ratings->where('skill_id', $skill->id)->first();

                $skill->max = $max;

                if (isset($rating)) {       // insert user rating data
                    $skill->rating = $rating->rating;
                    $skill->percent = $skill->rating / $max * 100;
                    $skill->background = $this->getBackgroundColor($skill->percent);
                } else {                    // insert placeholder data
                    $skill->rating = 0;
                    $skill->percent = 0;
                    $skill->background = '#e5e5e5';
                }
            }
        }

        return $categories;
    }

    public function getBackgroundColor($percent)
    {
        if ($percent > 80) {
            return '#ffcf36';
        } elseif ($percent > 50) {
            return '#f4a300';
        } elseif ($percent > 25) {
            return '#ef7d00';
        }

        return '#ed6000';
    }

}