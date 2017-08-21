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
