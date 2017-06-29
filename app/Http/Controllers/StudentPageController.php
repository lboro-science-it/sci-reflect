<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Page;
use App\Reflect\Reflect;
use App\Reflect\SelectionHelper;
use App\Round;
use Illuminate\Http\Request;

class StudentPageController extends Controller
{
    protected $selectionHelper;

    public function __construct(SelectionHelper $selectionHelper)
    {
        $this->selectionHelper = $selectionHelper;
    }

    public function process(Activity $activity, Round $round, Page $page, Reflect $reflect)
    {
        // store responses from the HTTP request
        // todo: check role/permissions
        $selections = $this->requestHelper->getNumericParameters();
        $this->selectionHelper->insertOrUpdateSelections($round, Auth::user());

        // do anything necessary for the submit action (next, prev, start, done)
        // returns a view with data - either page or chart
        return $reflect->processAction();
    }
}
