<?php

namespace App\Reflect\Formats;

use App\Reflect\Formats\BaseFormat;
use Illuminate\Http\Request;
use stdClass;

class LinearFormat extends BaseFormat
{
    protected $request;

    /**
     * Register format-specific actions (i.e. other than next, prev, done, resume)
     * and their functions, in the format:
     * action => functionName
     *
     */
    protected $formatActions = [

    ];

    /**
     * Merges format-specific actions with base actions.
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->actions = array_merge($this->actions, $this->formatActions);
        $this->selectionHelper = app('SelectionHelper');
        $this->reflect = app('Reflect');
    }

    public function done()
    {
        // generate chart data if necessary
        // return the chart view with data
    }

    public function getData($round, $page, $user)
    {
        $data = new stdClass();

        $indicators = $page->getIndicatorIds();

        $data->selections = $this->selectionHelper->getSelectionsFromIndicators($indicators, $round, $user);
        $data->choices = $this->reflect->getChoices();
        $data->content = $page->getContent();
        $data->hasNext = $this->hasNextPage($page, $round);
        $data->hasPrev = $this->hasPrevPage($page, $round);
        $data->hasDone = $round->isComplete($user);
        $data->roundNumber = $round->round_number;
        $data->pageNumber = $page->pivot->page_number;
        $data->totalPages = $round->pages->count();

        return $data;
    }

    public function getNextPage($page, $round)
    {
        if ($this->hasNextPage($page, $round)) {
            return $round->pages->where('pivot.page_number', $page->pivot->page_number + 1)->first();
        }

        return $page;
    }

    public function getPrevPage($page, $round)
    {
        if ($this->hasPrevPage($page, $round)) {
            return $round->pages->where('pivot.page_number', $page->pivot->page_number - 1)->first();
        }

        return $page;
    }

    public function hasNextPage($page, $round)
    {
        return $page->pivot->page_number < $round->pages->count();
    }

    public function hasPrevPage($page, $round)
    {
        return $page->pivot->page_number > 1;
    }

    public function next($round, $page, $user)
    {
        $nextPage = $this->getNextPage($page, $round);
        return view('page.linear.show')
        ->with('pageData', $this->getData($round, $nextPage, $user));
    }

    public function prev($round, $page, $user)
    {
        $prevPage = $this->getPrevPage($page, $round);
        return view('page.linear.show')
        ->with('pageData', $this->getData($round, $prevPage, $user));
    }

    /**
     * Stores the selections in database, determines the action from the HTTP
     * POST request, calls the relevant function which will return a view or redirect.
     *
     */
    public function process($round, $page, $user)
    {
        $this->selectionHelper->insertOrUpdateSelections($round, $user);

        $action = $this->getAction();
        return $this->$action($round, $page, $user);
    }

    public function resume($round, $page, $user)
    {
        return view('page.linear.show')
        ->with('pageData', $this->getData($round, $page, $user));
    }
}