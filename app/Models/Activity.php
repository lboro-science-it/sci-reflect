<?php

namespace App;

use Auth;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Activity extends Model
{
    protected $fillable = [
        'close_date', 'format', 'name', 'open_date', 'resource_link_record_id', 'consumer_pk',
    ];

    protected $hidden = [
        'consumer_pk', 'resource_link_record_id', 'status'
    ];

    /**
     * functions
     */

    public function getCategories()
    {
        if (!$this->relationLoaded('categories')) {
            $categories = $this->categories->sortBy('name')->sortBy('number');
            $this->setRelation('categories', $categories);
        }

        return $this->categories;
    }

    public function getRoundsData()
    {
        $rounds = $this->rounds->sortBy('round_number');
        $currentRoundNumber = Auth::user()->currentRound;

        $roundsData = new stdClass();

        $roundsData->completed = collect(array());
        $roundsData->future = collect(array());
        $roundsData->current = null;

        foreach($rounds as $round) {
            $round->viewable = $round->isViewable(Auth::user());
            if (is_null($currentRoundNumber) || $round->round_number < $currentRoundNumber) {
                $round->completion = '100%';
                $round->completionDecimal = 1.0;
                $roundsData->completed->push($round);
            } elseif ($round->round_number == $currentRoundNumber) {
                $round->completion = Auth::user()->getCompletion($round);
                $round->completionDecimal = Auth::user()->getCompletionDecimal($round);
                $roundsData->current = $round;
            } else {
                $round->completion = null;
                $round->completionDecimal = null;
                $roundsData->future->push($round);
            }
        }

        return $roundsData;        
    }

    /**
     * Returns a collection of activity's skills in render order
     * @return Collection
     */
    public function getSkills()
    {
        if (!isset($this->skills)) {
            $skills = collect(array());

            foreach($this->rounds as $round) {
                $skills = $skills->merge($round->getSkills());
            }

            $skills = $skills->unique('id');
            $categories = $this->getCategories();
            $sortedSkills = collect(array());

            foreach ($categories as $category) {
                $sortedSkills = $sortedSkills->merge($skills->where('category_id', $category->id)->sortBy('title')->sortBy('number'));
            }
            $this->skills = $sortedSkills;

        }

        return $this->skills;
    }

    /**
     * Returns a collection of categories containing skills for rendering
     * @return Collection
     */
    public function getSkillsInCategories()
    {
        $skills = $this->getSkills();
        $categories = $this->getCategories()->whereIn('id', $skills->pluck('category_id')->unique());

        foreach($categories as $category) {
            $category->setRelation('skills', $skills->where('category_id', $category->id));
        }

        return $categories;
    }

    /**
     * Returns true if the current date falls within the activity's open_date
     * and close_date, if they are set, or true if they are not set.
     * @return bool
     */
    public function isOpen()
    {
        if ($this->status == 'open') {
            $now = new DateTime();
            $from = new DateTime($this->open_date);
            $to = new DateTime($this->close_date);
            if ($now >= $from && $now <= $to) {
                return true;
            }
        }

        return false;
    }

    /**
     * Relationships
     */

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function choices()
    {
        return $this->hasMany('App\Choice');
    }

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function pages()
    {
        return $this->hasMany('App\Page');
    }

    public function rounds()
    {
        return $this->hasMany('App\Round');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot([
            'complete', 'current_page', 'current_round', 'group_id', 'lti_user_id', 'role'
        ])->withTimestamps();
    }
}
