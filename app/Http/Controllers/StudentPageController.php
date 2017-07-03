<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Page;
use App\Round;
use Auth;
use Illuminate\Http\Request;

class StudentPageController extends Controller
{
    /**
     * Processes submitted data, updating student data in DB,
     * returns view according to the action submitted & the round format.
     *
     * @return View
     */
    public function process(Activity $activity, Round $round, Page $page)
    {
        $formatClass = app($round->format);
        // todo: check role / permissions
        return $formatClass->process($round, $page, Auth::user());
    }

}