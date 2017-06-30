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

    /**
     * Takes submitted form data, updates any data as required, and returns
     * the necessary view depending on the action
     *
     * @return View
     */
    public function processAction()
    {
        $round = $this->request->route('round');


        // todo: direct to different functions based on round format

        // then from there, direct to different functions based on user role

        // then use those to actually do the action

        // todo: use $this->formats to dynamically insert class for action processing depending on round
        // todo: also use that to generate the database / maybe change db so instead of enum it's just a string
        // and then we can add new formats here that are reflected in the forms etc

        $formatClass = app($round->format);



        if ($this->request->input('resume')) {
            // show the current page as stored in activity pivot (if linear mode)
            // non-linear, show the user's current page in the category - so we would need another m2m table category_user
            // which stores for each category a current page and maybe round
        }

        if ($this->request->input('next')) {
            // show the next page in relation to either the round or the category
            // (depending on round format)
        }

        if ($this->request->input('prev')) {
            // show the prev page in relation to either the round of the category
            // (depending on round format)
        }

        if ($this->request->input('done')) {
            // if linear mode, prepare the data and show the chart
            // if nonlinear mode, return to the activity dashboard
            // unless that's all categories completed, in which case show the chart(?)
        }
    }

}