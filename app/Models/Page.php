<?php

namespace App;

use App\Reflect\BlockContentParser;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $visible = [
        'id',
        'title',
        'blockPivots',
        'skillPivots'
    ];

    protected $fillable = [
        'activity_id',
        'title'
    ];

    // Relationship methods 

    /**
     * Model for the pivot table between pages and blocks, used when pages
     * are rendered in activity setup, so in the browser the correct block
     * can be referred to via its ID.
     *
     */
    public function blockPivots()
    {
        return $this->hasMany('App\BlockPage');
    }

    public function blocks()
    {
        return $this->belongsToMany('App\Block')->withPivot('position');
    }

    public function roundPivots()
    {
        return $this->hasMany('App\PageRound');
    }

    public function rounds()
    {
        return $this->belongsToMany('App\Round')->withPivot('page_number');
    }

    /**
     * Model for the pivot between pages and skills. When pages are rendered
     * in activity setup, they are rendered with an array of pageSkills,
     * that array contains ids of all skills which are present in a separate
     * array.
     *
     */
    public function skillPivots()
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
