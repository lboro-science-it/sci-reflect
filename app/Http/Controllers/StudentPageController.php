<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Page;
use App\Round;
use Auth;
use Illuminate\Http\Request;

class StudentPageController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Processes submitted data, updating student data in DB,
     * returns view according to the action submitted & the round format.
     *
     * @return View
     */
    public function process(Activity $activity, Round $round, Page $page)
    {
        $formatClassName = '\App\Reflect\Formats\\' . $round->format . '\\Page';
        $formatClass = new $formatClassName($this->request);

        // todo: check role / permissions
        return $formatClass->processPage($round, $page, Auth::user());
    }

}