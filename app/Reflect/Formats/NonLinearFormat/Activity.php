<?php

namespace App\Reflect\Formats\NonLinearFormat;

use App\Reflect\Formats\BaseFormat;
use Illuminate\Http\Request;

class Activity extends BaseFormat
{
    protected $request;

    protected $view = 'activity.nonlinear.show';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function processActivity($activity)
    {
        // get the current round
        // get all the skills which are visible in the current round (via round->pages->skills)
        // get the categories of said skills
        // order the categories into their proper order
        // draw a box for each of them
        // calculate category's completion based on selections & indicators (per calculating round completion / page completion)
        // so todo: move completion into a comparison between selections and indicators, called from page/round/whatever
        // that's it really
        return view($this->activityView)
               ->with('activityData', $this->getData());
    }
}