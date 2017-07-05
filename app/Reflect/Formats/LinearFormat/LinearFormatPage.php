<?php

namespace App\Reflect\Formats\LinearFormat;

use stdClass;

class LinearFormatPage
{
    protected $activity, $round, $page, $user;

    protected $view = 'page.linear.show';

    /**
     * Merges format-specific actions with base actions.
     *
     */
    public function __construct($round, $page, $user)
    {
        $this->activity = request()->route('activity');
        $this->round = $round;
        $this->page = $page;
        $this->user = $user;

        $this->ratingsHelper = app('RatingsHelper');
        $this->selectionsHelper = app('SelectionsHelper');
        $this->reflect = app('Reflect');
    }

    public function done()
    {
        $chartData = $this->ratingsHelper->getChartData($this->round, $this->user);

        $this->user->incrementRound();

        return view('chart.single')
        ->with('chartData', $chartData);
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
        $data->hasDone = $this->round->isComplete($this->user);
        $data->hasNext = $this->hasNextPage($page);
        $data->hasPrev = $this->hasPrevPage($page);
        $data->pageNumber = $page->pivot->page_number;
        $data->pageTitle = $page->title;
        $data->roundNumber = $this->round->round_number;
        $data->selections = $this->selectionsHelper->getSelectionsFromIndicators($page->getIndicators(), $this->round, $this->user);
        $data->sidebar = $this->getSidebar($page);
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

    public function getSidebar($page)
    {
        $sidebar = collect(array());
        $pages = $this->round->pages->sortBy('pivot.page_number');
        
        foreach($pages as $sidebarPage) {
            $sidebarPage->complete = $sidebarPage->isComplete($this->round, $this->user);
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

    public function makePageView($page)
    {
        return view($this->view)
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
     * Action for when 'resume' is clicked.
     * @return View
     */
    public function resume()
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
    }
}