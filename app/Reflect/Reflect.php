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
     * These will be bound into the service container in ReflectFormatProvider.php
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
        $this->activity = $request->route('activity');
    }

    /**
     * Returns a collection of choice options for users to choose between.
     * Globally at present, todo: make these configurable (in sets) per-skill.
     * @return collection
     */
    public function getChoices()
    {
        if (!isset($this->activity->choices)) {
            $this->activity->load([
                'choices' => function($q) {
                    $q->orderBy('value');
                }
            ]);
        }

        return $this->activity->choices;
    }

    /**
     * Returns array of class names from $this->formats.
     *
     * @return array
     */
    public function getFormatClassNames()
    {
        return array_keys($this->formats);
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