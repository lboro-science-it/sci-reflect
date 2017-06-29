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

    private function deleteSelections($selectionsToDelete)
    {
        if (count($selectionsToDelete) > 0) {
            DB::table('selections')->whereIn('id', $selectionsToDelete)
            ->delete();
        }
    }

    // Returns array of the numeric parameters (keys) and their values
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

    // todo: refactor
    // todo: validate input
    public function insertOrUpdateSelections($round, $user)
    {
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

    public function insertSelections($selectionsToInsert)
    {
        if (count($selectionsToInsert) > 0) {
            DB::table('responses')->insert($selectionsToInsert);
        }
    }
}