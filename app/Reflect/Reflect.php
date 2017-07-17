<?php

namespace App\Reflect;

use Auth;
use Illuminate\Http\Request;

// helper functions
class Reflect
{
    protected $activity;

    /**
     * Register format classes.
     *
     */
    protected $formats = [
        'linear' => [
            'activity' => \App\Reflect\Formats\LinearFormat\Activity::class,
            'page' => \App\Reflect\Formats\LinearFormat\Page::class,
            'display_name' => 'Linear'
        ],
        'nonlinear' => [
            'activity' => \App\Reflect\Formats\NonLinearFormat\Activity::class,
            'page' => \App\Reflect\Formats\NonLinearFormat\Page::class,
            'display_name' => 'NonLinear'
        ]
    ];

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns a collection of choice options for users to choose between.
     * Globally at present, todo: make these configurable (in sets) per-skill.
     * @return collection
     */
    public function getChoices()
    {
        $activity = $this->request->route('activity');

        if (!isset($activity->choices)) {
            $activity->load([
                'choices' => function($q) {
                    $q->orderBy('value');
                }
            ]);
        }

        return $activity->choices;
    }

    public function getActivityFormatClass($format)
    {
        return new $this->formats[$format]['activity']($this->request);
    }

    public function getCurrentRoundFormat()
    {
        $activity = $this->request->route('activity');
        $round = $activity->rounds->where('round_number', Auth::user()->currentRound)->first();

        $format = isset($round->format) ? $round->format : $activity->format;

        return $format;
    }

    public function getPageFormatClass($format)
    {
        return new $this->formats[$format]['page']($this->request);
    }

    /**
     * Returns array of formats: ClassName => DisplayName.
     *
     * @return array
     */
    public function getFormatDisplayNames()
    {
        $returnFormats = [];
        foreach ($this->formats as $formatName => $data) {
            $returnFormats[$formatName] = $data['display_name'];
        }

        return $returnFormats;
    }

}