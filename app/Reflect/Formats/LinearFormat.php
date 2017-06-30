<?php

namespace App\Reflect\Formats;

use App\Reflect\Formats\BaseFormat;
use Illuminate\Http\Request;

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
    }

    public function done()
    {
        // generate chart data if necessary
        // return the chart view with data
    }

    public function getData($round, $page, $user)
    {

    }

    public function next()
    {
        // use current round / page to determine next page
        // return view + any of user's existing responses etc
    }

    public function prev()
    {
        // use current round / page to determine prev page
        // return view + user's existing responses
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
        return view('page.show')
        ->with('pageData', $this->getData($round, $page, $user));
    }
}