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
     * Handle a post to a/{activity}/student/r/{round}/p/{page} by looking up
     * $round's format and redirecting the request there.
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