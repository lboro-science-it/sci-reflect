<?php

namespace App\Reflect;

use Illuminate\Http\Request;

// helper functions
class Reflect
{
    protected $activity;

    /**
     * Register Format classes and their display name (for forms)
     * ClassName => DisplayName
     *
     */
    protected $formats = [
        'LinearFormat' => 'Linear',
        'NonLinearFormat' => 'Nonlinear'
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

    /**
     * Returns array of formats: ClassName => DisplayName.
     *
     * @return array
     */
    public function getFormats()
    {
        return $this->formats;
    }

}