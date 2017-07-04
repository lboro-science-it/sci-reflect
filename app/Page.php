<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function blocks()
    {
        return $this->belongsToMany('App\Block')->withPivot('position');
    }

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

    public function isComplete($round, $user)
    {
        $selectionsHelper = app('SelectionsHelper');
        $indicators = $this->getIndicators();

        if (count($indicators) > 0) {
            $selections = collect($selectionsHelper->getSelectionsFromIndicators($indicators, $round, $user));
        }

        return null;
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill')->withPivot('position');
    }
}
