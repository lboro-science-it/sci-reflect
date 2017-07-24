<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

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

    protected $loadedIndicators = false;
    protected $loadedCategories = false;

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
            $categories = $skills->pluck('category')->unique()->sortBy('name')->sortBy('number');
            $sortedSkills = collect(array());

            foreach ($categories as $category) {
                $sortedSkills = $sortedSkills->merge($skills->where('category_id', $category->id)->sortBy('title')->sortBy('number'));
            }
            $this->skills = $sortedSkills;

        }

        return $this->skills;
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
     * Performs a lazy eager load of the required data for rendering charts
     * or skills only if this data is not already loaded.
     * @return void
     */
    public function loadIndicatorsWithCategory()
    {
        if (!$this->loadedIndicators || !$this->loadedCategories) {
            $this->rounds->load([
                'pages.skills.indicators',
                'pages.skills.category'
            ]);

            $this->loadedCategories = true;
            $this->loadedIndicators = true;
        }
    }

    /**
     * Performs a lazy eager load of the required data for calculating
     * round completions, only if this data is not already loaded.
     * @return void
     */
    public function loadIndicators()
    {
        if (!$this->loadedIndicators) {
            $this->rounds->load([
                'pages.skills.indicators'
            ]);

            $this->loadedIndicators = true;
        }
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
