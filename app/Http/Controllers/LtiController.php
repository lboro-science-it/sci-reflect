<?php

namespace App\Http\Controllers;

use App\Lti\LtiToolProvider;
use Auth;
use DateTime;
use Illuminate\Http\Request;

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
                if (!Auth::check() || Auth::user()->id != $tool->activity_id) {
                    Auth::loginUsingId($tool->user_id);
                }

                if ($tool->role == 'staff') {
                    return redirect('a/' . $tool->activity_id);
                }

                return redirect('a/' . $tool->activity_id . '/student');
            } else {
                return view('lti.error');
            }
        } else {                // No cookies, show splash to force creation
            // todo: if failed to set cookie (i.e. would show splash twice)
            // show a link to open the tool in a new window instead
            $request->session()->put('live', 1);
            return view('lti.splash')
            ->with('params', $request->input());
        }
    }
}
