<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Lti\LtiToolProvider;

class LtiController extends Controller
{

    /**
     * Handle LTI launch or return form for manual resubmission
     *
     * @return View
     */
    public function launch(Request $request, LtiToolProvider $tool)
    {
        // todo: get activity record to see if it's open

        if ($request->session()->has('live')) {     // cookies are working
            $tool->handleRequest();
            if ($tool->ok) {
                Auth::loginUsingId($tool->user_id);
                return redirect('activity/' . $tool->activity_id);
            } else {
                return view('lti.error');
            }
        } else {                // No cookies, show splash to force creation
            $request->session()->put('live', 1);
            return view('lti.splash')
            ->with('params', $request->input());
        }
    }
}
