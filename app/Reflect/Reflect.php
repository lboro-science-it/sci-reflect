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
        'linear' => 'Linear',
        'nonlinear' => 'NonLinear'
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

    public function getCurrentRoundFormat()
    {
        $activity = $this->request->route('activity');
        $round = $activity->rounds->where('round_number', Auth::user()->currentRound)->first();

        $format = isset($round->format) ? $round->format : $activity->format;

        return $format;
    }

    /**
     * Returns array of formats: ClassName => DisplayName.
     *
     * @return array
     */
    public function getFormatDisplayNames()
    {
        return $this->formats;
    }

}