<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function blocks()
    {
        return $this->belongsToMany('App\Block')->withPivot('position');
    }

    /**
     * Returns the page's blocks and skills, sorted by position in their
     * respective pivot tables.
     * @return collection
     */
    public function getContent()
    {
        $content = collect(array());

        $this->skills->each(function ($item) use ($content) {
            $content->push($item);
        });

        $this->blocks->each(function ($item) use ($content) {
            $content->push($item);
        });

        return $content->sortBy('pivot.position');
    }

    /**
     * Returns an array of the indicators present on this page (via skills).
     * @return array
     */
    public function getIndicators()
    {
        // todo: refactor.
        $indicators = array();

        foreach($this->skills as $skill) {
            foreach($skill->indicators as $indicator) {
                array_push($indicators, $indicator);
            }
        }

        return $indicators;
    }

    /**
     * Returns true if user has a selection for each indicator on the page,
     * false if there are indicators the user hasn't responded to, or null
     * if the page has no indicators (i.e. content only page)
     * @return bool
     */
    public function isComplete($round, $user)
    {
        $selectionsHelper = app('SelectionsHelper');
        $indicators = $this->getIndicators();

        if (count($indicators) > 0) {
            $selections = collect($selectionsHelper->getSelectionsFromIndicators($indicators, $round, $user));
            return !$selections->contains(null);
        }

        return null;
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill')->withPivot('position');
    }
}
