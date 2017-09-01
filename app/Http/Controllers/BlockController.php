<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function update(Activity $activity, $blockId, Request $request)
    {
        $block = $activity->blocks()->where('id', $blockId)->first();

        if (!isset($block)) {
            return redirect('eject');
        }

        $block->content = $request->input('content');
        $block->save();

        return response()->json($block->content, 200);
    }
}
