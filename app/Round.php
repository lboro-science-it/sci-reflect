<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function getCompletion($user)
    {
        $selectionsHelper = app('SelectionsHelper');
        $indicators = $this->getIndicators();
        $selections = collect($selectionsHelper->getSelectionsFromIndicators($indicators, $this, $user));
        $totalSelected = $selections->filter(function ($value) {
            return !is_null($value);
        })->count();

        return $totalSelected / count($indicators);
    }

    public function getIndicators()
    {
        // todo: refactor.
        $indicators = array();

        foreach($this->pages as $page) {
            $indicators = array_merge($indicators, $page->getIndicators());
        }

        return $indicators;
    }

    public function isComplete($user)
    {
        $selectionsHelper = app('SelectionsHelper');
        $indicators = $this->getIndicators();
        $selections = collect($selectionsHelper->getSelectionsFromIndicators($indicators, $this, $user));
        return !$selections->contains(null);
    }

    public function pages()
    {
        return $this->belongsToMany('App\Page')->withPivot(['page_number']);
    }
}
