<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function getIndicatorIds()
    {
        return array_column($this->getIndicators(), 'id');
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
        $selectionHelper = app('SelectionHelper');
        $indicators = $this->getIndicatorIds();
        $selections = collect($selectionHelper->getSelectionsFromIndicators($indicators, $this, $user));
        return !$selections->contains(null);
    }

    public function pages()
    {
        return $this->belongsToMany('App\Page')->withPivot(['page_number']);
    }
}
