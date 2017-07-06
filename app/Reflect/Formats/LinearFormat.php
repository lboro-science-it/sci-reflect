<?php

namespace App\Reflect\Formats;

use Debugbar;
use App\Reflect\Formats\BaseFormat;
use App\Reflect\Formats\LinearFormat\LinearFormatActivity;
use App\Reflect\Formats\LinearFormat\LinearFormatPage;
use Illuminate\Http\Request;
use stdClass;

class LinearFormat extends BaseFormat
{
    /**
     * Register format-specific actions (i.e. other than next, prev, done, resume)
     * and their functions, in the format:
     * action => functionName
     *
     */
    protected $formatActions = [
        'page' => 'page'
    ];

    /**
     * Returns data to render Linear format Activity dashboard. We know that
     * the StudentActivityComposer will provide us access to $activity->pivot,
     * $activity->rounds, $round->pages, $page->skills, $skill-indicators. 
     * @return bool
     */
    public function composeActivity($activity, $user)
    {
        $activityComposer = new LinearFormatActivity($activity, $user);

        return $activityComposer->getActivityViewData();
    }

    /**
     * Stores the selections in database, determines the action from the HTTP
     * POST request, calls the relevant function which will return a view or redirect.
     *
     */
    public function processPage($round, $page, $user)
    {
        $selectionsHelper = app('SelectionsHelper');
        $selectionsHelper->insertOrUpdateSelections($round, $user);

        $pageProcessor = new LinearFormatPage($round, $page, $user);
        $action = $this->getAction();

        $actionMethod = $action->action;
        $actionParam = $action->param;

        return $pageProcessor->$actionMethod($actionParam);
    }

}