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
        return view($this->activityView)
               ->with('activityData', $this->getData());
    }
}