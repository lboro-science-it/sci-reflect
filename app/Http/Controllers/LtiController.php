<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Lti\LtiToolProvider;

class LtiController extends Controller
{

    /**
     * Handles LTI launch, returning either the activity or a form
     * for manual resubmission to ensure cookie created in iframe.
     *
     * @return View or Redirect
     */
    public function launch(Request $request, LtiToolProvider $tool)
    {
        // todo: get activity record to see if it's open so we can
        // display closed directly without showing the splash

        if ($request->session()->has('live')) {     // cookies are working
            $tool->handleRequest();
            if ($tool->ok) {
                Auth::loginUsingId($tool->user_id);
                return redirect('a/' . $tool->activity_id);
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
