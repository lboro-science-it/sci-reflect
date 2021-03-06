<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Block;
use App\Round;
use DB;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    /**
     * Delete the specified round resource, only if the user has access to it,
     * which is the case if they are staff in activity.
     *
     */
    public function delete(Activity $activity, $roundId, Request $request)
    {
        $round = $activity->rounds->where('id', $roundId)->first();

        if (isset($round)) {
            // delete the round's pivots and the round
            $round->pagePivots()->delete();
            $round->delete();

            // update the remaining rounds' round numbers
            $remainingRounds = $activity->rounds->filter(function ($round) use ($roundId) {
                return $round->id != $roundId;
            })->sortBy('round_number');

            // build sql case query for each round and the roundNumber it should have
            $roundNumber = 1;
            $cases = '';
            foreach ($remainingRounds as $round) {
                $cases .= "when id = $round->id then $roundNumber ";
                $roundNumber++;
            }

            DB::statement("UPDATE rounds SET round_number = (case $cases end) WHERE activity_id = $activity->id;");

            return response()->json(null, 204);
        }
    }

    /**
     * Displays the form for editing rounds, which also includes rounds'
     * related data (pages, skills, etc) so various bits of data are loaded.
     *
     */
    public function index(Activity $activity, Request $request)
    {
        // get rounds as a json string (array) indexed by round_number
        $activity->rounds->load([
            'pagePivots' => function($q) {
                $q->orderBy('page_number');
            }
        ]);
        $rounds = $activity->rounds->sortBy('round_number')->values()->toJson();

        // get pages with ids of blocks / skills as arrays
        $activity->pages->load([
            'blockPivots' => function($q) {
                $q->orderBy('position');
            },
            'skillPivots' => function($q) {
                $q->orderBy('position');
            }
        ]);
        $pages = $activity->pages->keyBy('id')->toJson();

        $blocks = $activity->blocks->keyBy('id')->toJson();
        
        // get skills with indicators preloaded as arrays
        $activity->skills->load([
            'indicators'
        ]);
        $skills = $activity->skills->sortBy('number')->keyBy('id')->toJson();

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

    /**
     * Store a round in the activity based on $request->input('title'),
     * returns the round object to be included in the browser object.
     *
     */
    public function store(Activity $activity, Request $request)
    {
        // so we know the user has access to the activity and is staff
        // so we just need to create the round...
        // todo: we also need to add a default page for the round in order for it not to break
        $round = new Round([
            'format' => $activity->format,
            'round_number' => $activity->rounds->count() + 1,
            'title' => $request->input('title'),
            'keep_visible' => true,
            'staff_rate' => true,
            'student_rate' => true
        ]);

        $round->activity_id = $activity->id;
        $round->save();

        $round->page_pivots = [];

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

        // create a block if there is content to go in it and one doesn't exist
        if ($round->block_id == null && $request->input('blockContent') != null) {
            $block = Block::create([
                'activity_id' => $activity->id,
                'content' => $request->input('blockContent')
            ]);
            $block->save();
            $round->block()->associate($block);
        // update the block's content to match posted content if a block is present
        } elseif ($round->block_id != null) {
            $block = $round->block;
            $block->content = $request->input('blockContent') ? $request->input('blockContent') : '';
            $block->save();
        }

        $round->save();

        // return the round object which also includes the block
        return $round;
    }

    /**
     * Updates page numbers in page_round pivot for $roundId based on
     * $request->input('pages') which contains data in the format page_id => page_number
     *
     */ 
    public function updatePageNumbers(Activity $activity, $roundId, Request $request)
    {
        // get the round from the activity
        $round = $activity->rounds()->where('id', $roundId)->first();

        // eject if the round isn't set, it means it belongs to a different activity
        // todo: put into middleware to detect that $roundId belongs to $activity
        if (is_null($round)) {
            return redirect('eject');
        }

        // generate the case statements to mass update the pivot table
        $cases = '';
        $page_pivots = [];
        foreach($request->input('pages') as $page_id => $page_number) {
            $cases .= "when page_id = $page_id then $page_number ";
            array_push($page_pivots, [
                'page_id' => $page_id,
                'page_number' => $page_number,
            ]);
        }

        usort($page_pivots, function($a, $b) {
            return $a['page_number'] <=> $b['page_number'];
        });

        DB::statement("UPDATE page_round SET page_number = (case $cases end) WHERE round_id = $roundId;");

        return response()->json($page_pivots, 200);
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

        return response()->json(null, 200);
    }
}
