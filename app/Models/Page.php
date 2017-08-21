<?php

namespace App;

use App\Reflect\BlockContentParser;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $visible = [
        'id',
        'title',
        'blockPages',
        'pageSkills'
    ];

    // Relationship methods 

    public function blockPages()
    {
        return $this->hasMany('App\BlockPage');
    }

    public function blocks()
    {
        return $this->belongsToMany('App\Block')->withPivot('position');
    }

    public function pageSkills()
    {
        return $this->hasMany('App\PageSkill');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill')->withPivot('position');
    }

    // Data gathering methods

    /**
     * Returns the page's blocks and skills, sorted by position in their
     * respective pivot tables.
     * @return collection
     */
    public function getContent()
    {
        $content = collect(array());

        $this->skills->each(function ($item) use ($content) {
            // sort indicators by number, or name failing that
            $item->setRelation('indicators', $item->indicators->sortBy('text')->sortBy('number'));
            $content->push($item);
        });

        $blockContentParser = new BlockContentParser();

        $this->blocks->each(function ($item) use ($content, $blockContentParser) {
            $item->content = $blockContentParser->parse($item->content);
            $content->push($item);
        });

        // sort all content (blocks and skills) by position in page
        return $content->sortBy('pivot.position');
    }

    /**
     * Returns an array of the indicators present on this page (via skills).
     * @return array
     */
    public function getIndicators()
    {
        if (!isset($this->indicators)) {
            $indicators = collect(array());

            foreach($this->skills as $skill) {
                foreach($skill->indicators as $indicator) {
                    $indicators->push($indicator);
                }
            }

            $this->indicators = $indicators->unique();
        }

        return $this->indicators;
    }

}
