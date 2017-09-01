<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Page;
use App\Round;
use DB;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Create a relationship between the block with the passed ID and the page.
     * Sets position to the last position on the page (i.e. after the page's
     * existing content)
     */
    public function addBlock(Activity $activity, $pageId, Request $request) 
    {
        // get the page and block from the activity to ensure the auth user has auth to edit them
        $page = $activity->pages()->where('id', $pageId)->first();
        $block = $activity->blocks()->where('id', $request->input('blockId'))->first();

        // log out the user if neither is set because it means they're up to no good
        if (!isset($page) || !isset($block)) {
            return redirect('eject');
        }

        // only add the block if it isn't already on the page
        if (null === $page->blockPivots()->where('block_id', $block->id)->first()) {
            // get page's other content so we know what position to put the block in at
            $blocks = $page->blocks()->get();
            $skills = $page->skills()->get();

            $newBlockPosition = 1 + $blocks->count() + $skills->count();

            $page->blocks()->attach($block, ['position' => $newBlockPosition]);

            return response()->json(['position' => $newBlockPosition], 200);
        } else {
            return response()->json(null, 204);
        }
    }

    /**
     * Delete the page with specified id, as long as the user is staff in the
     * given $activity (Middleware ensures this is the case) and as long as
     * the page belongs to the activity. Also deletes all relationships the
     * page has to rounds, and updates those rounds' page_numbers.
     * 
     */
    public function delete(Activity $activity, $pageId, Request $request)
    {
        // check the page exists in the activity
        $page = $activity->pages()->where('id', $pageId)->first();

        if (!isset($page)) {
            // user is trying to delete another activity's page
            return redirect('eject');
        }

        // deletes relationships to linked blocks / skills - may create orphans
        $page->blockPivots()->delete();
        $page->skillPivots()->delete();

        // delete relationships to rounds, update page_numbers for those rounds
        $rounds = $page->rounds()->get();
        if ($rounds->count()) {
            $page->roundPivots()->delete();
            $rounds->load('pagePivots');
            foreach ($rounds as $round) {
                $round->updatePageNumbers();
            }
        }
        $page->delete();

        return response()->json(null, 204);
    }

    /**
     * Stores a new page in the database based on $request->input('page').
     * If set as $request->input('roundId'), also creates relationship to round.
     *
     */
    public function store(Activity $activity, Request $request)
    {
        // create the page record
        $pageToCreate = $request->input('page');
        $pageToCreate['activity_id'] = $activity->id;
        $page = Page::create($pageToCreate);

        $response = [
            'page' => $page,
        ];

        $roundId = $request->input('roundId');
        // relate the page to rounds if needed
        if (isset($roundId)) {
            $round = $activity->rounds->where('id', $roundId)->first();

            // if round is not set then it's not in the activity, so the user is trying to do something shady
            if (!isset($round)) {
                return redirect('eject');
            }

            $pageNumber = $round->pages()->count() + 1;

            DB::table('page_round')->insert([
                'page_id' => $page->id,
                'round_id' => $roundId,
                'page_number' => $pageNumber,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $response['page_pivot'] = [
                'page_id' => $page->id,
                'page_number' => $pageNumber
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Unset the pivot relationship between $pageId and the thing in the 
     * request (block or skill)
     */
    public function unrelate(Activity $activity, $pageId, Request $request)
    {
        $page = $activity->pages()->where('id', $pageId)->first();

        if ($request->input('type') == 'block') {
            $pivotToDelete = $page->blockPivots()->where('block_id', $request->input('id'))->first();
        } else if ($request->input('type' == 'skill')) {
            $pivotToDelete = $page->skillPivots()->where('skill_id', $request->input('id'))->first();
        }

        // kick the user out if $pivotToDelete isn't set - it means they're trying to delete one they shouldn't be
        if (!isset($pivotToDelete)) {
            return redirect('eject');
        }

        $pivotToDelete->delete();

        $page->refreshContentPositions();

        return response()->json(null, 204);
    }

    /**
     * Updates page title, endpoint of save method in PageEdit.vue component
     *
     */ 
    public function update(Activity $activity, $pageId, Request $request)
    {
        // get page from $activity to ensure it is editable
        $page = $activity->pages()->where('id', $pageId)->first();

        // eject user if page isn't in this activity because they don't have authorisation
        if (!isset($page)) {
            return redirect('eject');
        }

        $page->title = $request->input('title');
        $page->save();

        return response()->json($page, 200);
    }
}
