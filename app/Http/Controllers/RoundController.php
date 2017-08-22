<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Round;
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
        $blocks = $activity->blocks->toJson();
        
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

    public function update(Activity $activity, Request $request)
    {

    }
}
