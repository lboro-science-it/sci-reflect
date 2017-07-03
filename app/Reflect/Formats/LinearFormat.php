<?php

namespace App\Reflect\Formats;

use App\Reflect\Formats\BaseFormat;
use Illuminate\Http\Request;
use stdClass;

class LinearFormat extends BaseFormat
{
    protected $activity, $selectionHelper, $reflect, $request;

    /**
     * Register format-specific actions (i.e. other than next, prev, done, resume)
     * and their functions, in the format:
     * action => functionName
     *
     */
    protected $formatActions = [

    ];

    protected $view = 'page.linear.show';

    /**
     * Merges format-specific actions with base actions.
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->actions = array_merge($this->actions, $this->formatActions);
        $this->activity = $request->route('activity');
        $this->ratingsHelper = app('RatingsHelper');
        $this->selectionHelper = app('SelectionHelper');
        $this->reflect = app('Reflect');
    }

    public function done()
    {
        $data = $this->ratingsHelper->getChartData($this->round, $this->user);

        return view('chart.single')
        ->with('data', $data);
    }

    /**
     * Prepares $page data specific to $this->round, with $this->user's responses.
     * @return Object
     */
    public function getData($page)
    {
        $data = new stdClass();

        $indicators = $page->getIndicatorIds();

        $data->selections = $this->selectionHelper->getSelectionsFromIndicators($indicators, $this->round, $this->user);
        $data->choices = $this->reflect->getChoices();
        $data->content = $page->getContent();
        $data->hasNext = $this->hasNextPage($page);
        $data->hasPrev = $this->hasPrevPage($page);
        $data->hasDone = $this->round->isComplete($this->user);
        $data->roundNumber = $this->round->round_number;
        $data->pageNumber = $page->pivot->page_number;
        $data->totalPages = $this->round->pages->count();

        return $data;
    }

    /**
     * Returns the next page in the round.
     * @return Page
     */
    public function getNextPage($page)
    {
        if ($this->hasNextPage($page)) {
            return $this->round->pages->where('pivot.page_number', $page->pivot->page_number + 1)->first();
        }

        return $page;
    }

    /**
     * Returns the previous page in the round.
     * @return Page
     */
    public function getPrevPage($page)
    {
        if ($this->hasPrevPage($page)) {
            return $this->round->pages->where('pivot.page_number', $page->pivot->page_number - 1)->first();
        }

        return $page;
    }

    /**
     * Returns true if $page has a next page in $this->round.
     * @return bool
     */
    public function hasNextPage($page)
    {
        return $page->pivot->page_number < $this->round->pages->count();
    }

    /**
     * Returns the true if $page has a prev page in $this->round.
     * @return bool
     */
    public function hasPrevPage($page)
    {
        return $page->pivot->page_number > 1;
    }

    /**
     * Action for when 'next' is clicked.
     * @return View
     */
    public function next()
    {
        $nextPage = $this->getNextPage($this->page);
        $this->updateUserPivot($nextPage);

        return view($this->view)
        ->with('pageData', $this->getData($nextPage));
    }

    /**
     * Action for when 'prev' is clicked.
     * @return View
     */
    public function prev()
    {
        $prevPage = $this->getPrevPage($this->page);
        $this->updateUserPivot($prevPage);

        return view($this->view)
        ->with('pageData', $this->getData($prevPage));
    }

    /**
     * Stores the selections in database, determines the action from the HTTP
     * POST request, calls the relevant function which will return a view or redirect.
     *
     */
    public function process($round, $page, $user)
    {
        $this->round = $round;
        $this->page = $page;
        $this->user = $user;

        $this->selectionHelper->insertOrUpdateSelections($round, $user);

        // action can be 'done', 'next', 'prev', 'resume' depending on which
        // submit button was clicked, calls relevant class method to deal with.
        $action = $this->getAction();
        return $this->$action();
    }

    /**
     * Action for when 'resume' is clicked.
     * @return View
     */
    public function resume()
    {
        return view($this->view)
        ->with('pageData', $this->getData($this->page));
    }

    /**
     * Updates $this->user's pivot for this activity to current round and page.
     * @return void
     */
    public function updateUserPivot($page)
    {
        $this->user->activities()->updateExistingPivot($this->activity->id, [
            'current_round' => $this->round->round_number,
            'current_page' => $page->pivot->page_number
        ]);
    }
}