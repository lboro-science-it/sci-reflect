<?php

namespace App\Reflect;

use Illuminate\Http\Request;
use DB;

class SelectionHelper
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Deletes all selections whose id is present in passed array.
     * @return void
     */
    private function deleteSelections($selectionsToDelete)
    {
        if (count($selectionsToDelete) > 0) {
            DB::table('selections')->whereIn('id', $selectionsToDelete)
            ->delete();
        }
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
        foreach ($indicators as $indicatorId) {
            $selection = $userSelections->where('indicator_id', $indicatorId)->first();
            $existingSelections[$indicatorId] = ($selection) ? $selection->choice_id : null;
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
        // todo: refactor
        // todo: validate input
        $selections = $this->getSelectionsFromRequest(); 

        if (count($selections) == 0) {
            return;
        }

        $existingSelections = $user->selections->where('round_id', '=', $round->id);

        $selectionsToInsert = array();
        $selectionsToDelete = array();

        foreach($selections as $indicatorId => $choiceId) {
            $existingSelection = $existingSelections->where('indicator_id', '=', $indicatorId)->first();

            // if user has responded to this indicator differently to new one, delete it
            if ($existingSelection && $existingSelection->choice_id != $choiceId) {
                array_push($selectionsToDelete, $existingSelection->id);
            }

            // if there is no response the same as the new response, insert it
            if (!($existingSelection && $existingSelection->choice_id == $choiceId)) {
                array_push($selectionsToInsert, [
                    'round_id' => $round->id,
                    'indicator_id' => $indicatorId,
                    'user_id' => $user->id,
                    'choice_id' => $choiceId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $this->deleteSelections($selectionsToDelete);
        $this->insertSelections($selectionsToInsert);
    }

    /**
     * Inserts selections from passed array.
     * @return void
     */
    public function insertSelections($selectionsToInsert)
    {
        if (count($selectionsToInsert) > 0) {
            DB::table('selections')->insert($selectionsToInsert);
        }
    }
}