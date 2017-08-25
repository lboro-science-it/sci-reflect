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
            'page_pivots' => []
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

            $response['page_pivots'] = [
                'page_id' => $page->id,
                'page_number' => $pageNumber
            ];
        }

        return response()->json($response, 200);
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
