<?php

namespace App\Reflect\Formats;

use App\Reflect\BlockContentParser;
use Auth;
use Illuminate\Http\Request;
use stdClass;

class BaseActivity
{
    protected $request;

    protected $activity = null;

    protected $user = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->activity = $request->route('activity');
        $this->user = Auth::user();

        $this->currentRound = $this->activity->rounds->where('round_number', $this->user->currentRound)->first();
        $this->previousRound = $this->getPreviousRound();
    }
    
    /**
     * Returns data for displaying the last completed chart.
     *
     */
    public function getChartData()
    {
        $chartHelper = app('ChartHelper');

        if (isset($this->previousRound)) {
            return $chartHelper->getChartData($this->previousRound, $this->user);
        }

        return $chartHelper->getChartData();
    }

    private function getPreviousRound()
    {
        $currentRoundNumber = $this->user->currentRound;

        if ($currentRoundNumber > 1) {
            return $this->activity->rounds->where('round_number', $currentRoundNumber - 1)->first();
        } else if (is_null($currentRoundNumber)) {
            return $this->activity->rounds->where('round_number', $this->activity->rounds->count())->first();
        }
    }

    public function getRoundContent()
    {
        if (isset($this->currentRound->block)) {
            $blockContentParser = new BlockContentParser();
            return $blockContentParser->parse($this->currentRound->block->content);
        }
    }
    
}