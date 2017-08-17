<?php

namespace App\Reflect\Formats\LinearFormat;

use App\Reflect\Formats\BasePage;
use App\Reflect\Reflect;
use Auth;
use Illuminate\Http\Request;
use stdClass;

class LinearPage extends BasePage
{
    protected $activity;
    protected $page;
    protected $request;
    protected $round;
    protected $user;

    protected $formatActions = [
        'page' => 'page',
        'save' => 'save'
    ];

    protected $view = 'page.linear.show';

    /**
     * Merges format-specific actions with base actions.
     *
     */
    public function __construct(Request $request, Reflect $reflect)
    {
        $this->request = $request;
        $this->activity = $request->route('activity');
        $this->round = $request->route('round');
        $this->page = $request->route('roundPage');
        $this->user = Auth::user();

        $this->eagerLoad();

        $this->selectionsHelper = app('SelectionsHelper');
        $this->reflect = $reflect;
    }

    /**
     * Increments user's round and redirects user to chart route for round.
     *
     * @return Redirect
     */
    public function done()
    {
        $this->user->incrementRound();

        return redirect('a/' . $this->activity->id . '/student/r/' . $this->round->round_number . '/chart');
    }

    private function eagerLoad()
    {
        $this->round->pages->load([
            'skills.indicators'
        ]);
    }

    /**
     * Prepares $page data specific to $this->round, with $this->user's responses.
     * @return Object
     */
    public function getData($page)
    {
        $data = new stdClass();

        $data->choices = $this->reflect->getChoices();
        $data->content = $page->getContent();
        $data->hasDone = $this->user->hasCompleted($this->round);
        $data->hasNext = $this->hasNextPage($page);
        $data->hasPrev = $this->hasPrevPage($page);
        $data->hasSave = $this->hasSave($page);
        $data->pageNumber = $page->pivot->page_number;
        $data->pageTitle = $page->title;
        $data->roundNumber = $this->round->round_number;
        $data->roundTitle = $this->round->title;
        $data->selections = $this->selectionsHelper->getSelectionsFromIndicators($page->getIndicators(), $this->round, $this->user);
        $data->sidebar = $this->getSidebar($page);
        $data->totalPages = $this->round->pages->count();
        $data->view = $this->view;

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

    public function getSidebar($page)
    {
        $sidebar = collect(array());
        $pages = $this->round->pages->sortBy('pivot.page_number');
        
        foreach($pages as $sidebarPage) {
            $sidebarPage->complete = $this->user->hasCompleted($this->round, $sidebarPage);
            $sidebarPage->hasIndicators = $sidebarPage->indicators->count() > 0;
            $sidebarPage->current = $sidebarPage->id == $page->id ? true : false;
            $sidebarPage->pageNumber = $sidebarPage->pivot->page_number;
            $sidebar->push($sidebarPage);
        }

        return $sidebar;
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
     * Returns the true if $page doesn't have a next page and has skills.
     * @return bool
     */
    public function hasSave($page)
    {
        return !$this->hasNextPage($page) && $page->skills->count() > 1;
    }

    public function makePageView($page)
    {
        return view($this->pageView)
        ->with('pageData', $this->getData($page));
    }

    /**
     * Action for when 'next' is clicked.
     * @return View
     */
    public function next()
    {
        $nextPage = $this->getNextPage($this->page);
        $this->updateUserPivot($nextPage);

        return $this->makePageView($nextPage);
    }

    /**
     * Action for when a specific page link is clicked.
     * @return View
     */
    public function page($pageNumber)
    {
        $page = $this->round->pages->where('pivot.page_number', $pageNumber)->first();
        $this->updateUserPivot($page);

        return $this->makePageView($page);
    }

    /**
     * Action for when 'prev' is clicked.
     * @return View
     */
    public function prev()
    {
        $prevPage = $this->getPrevPage($this->page);
        $this->updateUserPivot($prevPage);

        return $this->makePageView($prevPage);
    }

    /**
     * Stores the selections in database, determines the action from the HTTP
     * POST request, calls the relevant function which will return a view or redirect.
     *
     */
    public function processPage()
    {
        $this->selectionsHelper->insertOrUpdateSelections($this->round, $this->user);

        $action = $this->getAction();

        $actionMethod = $action->action;
        $actionParam = $action->param;

        return $this->$actionMethod($actionParam);
    }

    /**
     * Action for when 'resume' is clicked.
     * @return View
     */
    public function resume()
    {
        return $this->makePageView($this->page);
    }

    public function save()
    {
        return $this->makePageView($this->page);
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

        // update session to match.
        if ($this->user->id == Auth::user()->id) {
            $activities = $this->request->session()->get('activities');
            $activities[$this->activity->id]['currentRound'] = $this->round->round_number;
            $activities[$this->activity->id]['currentPage'] = $page->pivot->page_number;
            $this->request->session()->put('activities', $activities);

        }
    }
}