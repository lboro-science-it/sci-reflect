<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Round;
use DB;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function delete(Activity $activity, Request $request)
    {

    }

    public function index(Activity $activity, Request $request)
    {
        // get rounds as a json string (array) indexed by round_number
        $activity->rounds->load([
            'pageRounds'
        ]);
        $rounds = $activity->rounds->sortBy('round_number')->values()->toJson();

        // get pages with ids of blocks / skills as arrays
        $activity->pages->load([
            'blockPages',
            'pageSkills'
        ]);
        $pages = $activity->pages->toJson();

        // get blocks
        $blocks = $activity->blocks->keyBy('id')->toJson();
        
        // get skills with indicators preloaded as arrays
        $activity->skills->load([
            'indicators'
        ]);
        $skills = $activity->skills->sortBy('number')->values()->toJson();

        // get categories
        $categories = $activity->categories->sortBy('name')->sortBy('number')->values()->toJson();

        $choices = $activity->choices->sortBy('value')->values()->toJson();

        return view ('staff.rounds')->with('blocks', $blocks)
                                   ->with('categories', $categories)
                                   ->with('choices', $choices)
                                   ->with('pages', $pages)
                                   ->with('rounds', $rounds)
                                   ->with('skills', $skills);
    }

    public function store(Activity $activity, Request $request)
    {
        // so we know the user has access to the activity and is staff
        // so we just need to create the round...
        $round = new Round([
            'format' => $activity->format,
            'round_number' => $activity->rounds->count() + 1,
            'title' => $request->input('title')
        ]);

        $round->activity_id = $activity->id;
        $round->save();

        return $round;
    }

    /** 
     * Update the round and its associated block based on $request->input,
     * which contains 'round', a copy of the round object, and blockContent,
     * to be put in the round's related block's content column.
     *
     */
    public function update(Activity $activity, $roundId, Request $request)
    {
        // get the round from the $activity object, ensuring the user has authority to edit it
        $round = $activity->rounds()->where('id', $roundId)->first();

        // update the fields
        $roundUpdates = $request->input('round');

        $round->fill($roundUpdates);
        $round->open_date = $roundUpdates['open_date'] == '' ? null : $roundUpdates['open_date'];
        $round->close_date = $roundUpdates['close_date'] == '' ? null : $roundUpdates['close_date'];
        $round->keep_visible = $roundUpdates['keep_visible'] == 'true' ? 1 : 0;
        $round->staff_rate = $roundUpdates['staff_rate'] == 'true' ? 1 : 0;
        $round->student_rate = $roundUpdates['student_rate'] == 'true' ? 1 : 0;

        // if the round's block id is null and $request->blockContent == '' do nothing
        // if it's null and blockContent is set, create a block
        // if it's not null, update the block to the same content as blockContent

        $round->save();

        return $round;
    }

    /**
     * Updates round_number based on $request->input('rounds') which should
     * be an array of $round_id => $round_number.
     *
     */
    public function updateRoundOrder(Activity $activity, Request $request)
    {
        $round_ids = array_keys($request->input('rounds'));

        // check round_ids all belong to the activity and therefore user has permission to change them
        // todo: move this to middleware
        $rounds = $activity->rounds->whereIn('id', $round_ids);
        if (count($round_ids) != $rounds->count()) {
            return redirect('eject');
        }

        // build cases string for update query
        $cases = '';
        foreach($request->input('rounds') as $round_id => $round_number) {
            $cases .= "when id = $round_id then $round_number "; 
        }

        DB::statement("UPDATE rounds SET round_number = (case $cases end) WHERE id IN (" . implode(", ", $round_ids) .");");

        return 'success';
    }
}
