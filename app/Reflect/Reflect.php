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
        $this->activity = $request->route('activity');
    }

    public function getBackgroundColor($percent)
    {
        if ($percent > 80) {
            return '#ffcf36';
        } elseif ($percent > 50) {
            return '#f4a300';
        } elseif ($percent > 25) {
            return '#ef7d00';
        }

        return '#ed6000';
    }

    /**
     * Returns a collection of choice options for users to choose between.
     * Globally at present, todo: make these configurable (in sets) per-skill.
     * @return collection
     */
    public function getChoices()
    {
        return $this->activity->choices->sortBy('value');
    }

    /**
     * Returns the format of the Auth::user()'s current round, or the $activity
     * format failing that.
     * @return String $format
     */
    public function getCurrentRoundFormat()
    {
        $round = $this->activity->rounds->where('round_number', Auth::user()->currentRound)->first();

        $format = isset($round->format) ? $round->format : $this->activity->format;

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