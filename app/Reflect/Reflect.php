<?php

namespace App\Reflect;

use Illuminate\Http\Request;

// helper functions
class Reflect
{
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
    }

    /**
     * Returns a collection of choice options for users to choose between.
     * Globally at present, todo: make these configurable (in sets) per-skill.
     * @return collection
     */
    public function getChoices()
    {
        $activity = $this->request->route('activity');
        return $activity->choices()->orderBy('value')->get();
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