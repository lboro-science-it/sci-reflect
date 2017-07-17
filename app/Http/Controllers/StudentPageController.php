<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Page;
use App\Reflect\Reflect;
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
     * Handle a post to a/{activity}/{format}/r/{round}/p/{page}
     * Creates an instance of $round's Format Page class to process + render.
     *
     * @return View
     */
    public function process(Activity $activity, $format, Round $round, Page $page, Reflect $reflect)
    {
        $formatClass = $reflect->getPageFormatClass($format);

        // todo: check role / permissions
        return $formatClass->processPage($round, $page, Auth::user());
    }

}