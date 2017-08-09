<?php

namespace App\Reflect;

use DB;
use Debugbar;
use Illuminate\Http\Request;

class SelectionsHelper
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns an array with $user's response for each indicator in $indicators
     * in $round, or null if there is no response. Use in views:
     * @if($existingResponses[$indicator->id] == $choice->id) checked @endif
     * 
     * @return array
     */
    public function getSelectionsFromIndicators($indicators, $round, $user)
    {
        $userSelections = $user->selections->where('round_id', '=', $round->id);

        $existingSelections = array();
        foreach ($indicators as $indicator) {
            $selection = $userSelections->where('indicator_id', $indicator->id)->first();
            $existingSelections[$indicator->id] = ($selection) ? $selection->choice_id : null;
        }

        return $existingSelections;
    }

    /**
     * Returns a key => value array of POST parameters where key is numeric
     * selection_id => choice_id
     * @return array
     */
    private function getSelectionsFromRequest()
    {
        $parameters = array();
        foreach($this->request->input() as $key => $value) {
            if (is_numeric($key)) {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }

    /**
     * Updates $user's database selections for the $round based on POSTed data.
     * @return void
     */
    public function insertOrUpdateSelections($round, $user)
    {
        // get $requestSelections from request in format $indicatorId => $choiceId
        $requestSelections = $this->getSelectionsFromRequest();

        if (count($requestSelections) > 0) {
            // delete existing selections matching the indicator_id, user_id and round_id
            $indicatorIdsToDelete = array_keys($requestSelections);
            DB::table('selections')->where('user_id', $user->id)
                                   ->where('round_id', $round->id)
                                   ->whereIn('indicator_id', $indicatorIdsToDelete)
                                   ->delete();

            // insert new versions of those selections
            $selectionsToInsert = [];
            foreach($requestSelections as $indicatorId => $choiceId) {
                array_push($selectionsToInsert, [
                    'round_id' => $round->id,
                    'indicator_id' => $indicatorId,
                    'user_id' => $user->id,
                    'choice_id' => $choiceId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::table('selections')->insert($selectionsToInsert);
            $user->load(['selections' => function ($q) use ($round) {
                $q->where('round_id', '=', $round->id);
            }]);
        }
    }

}