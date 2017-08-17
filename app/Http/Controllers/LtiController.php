<?php

namespace App\Http\Controllers;

use App\Lti\LtiToolProvider;
use Auth;
use DateTime;
use Illuminate\Http\Request;

class LtiController extends Controller
{

    /**
     * Handle LTI launch, return activity if we know cookies are fine,
     * or a form to ensure cookies are created in iframe.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\Lti\LtiToolProvider $tool
     * @return View or Redirect
     */
    public function launch(Request $request, LtiToolProvider $tool)
    {
        // todo: get activity, display closed directly without splash if needed

        // if session has the key it means cookies are working
        if ($request->session()->has('live')) {
            $tool->handleRequest();

            if ($tool->ok) {
                // auth the launching user if they aren't already authed
                if (!Auth::check() || Auth::user()->id != $tool->user_id) {
                    Auth::loginUsingId($tool->user_id);
                }

                // store user's pivot data for the activity in the session
                // so we don't have to query the pivot stuff every request
                $activities = $request->session()->has('activities') ? $request->session()->get('activities') : [];
                $activities[$tool->activity_id] = [
                    'activity_id' => $tool->activity_id,
                    'currentRound' => $tool->currentRoundNumber,
                    'currentPage' => $tool->currentPageNumber,
                    'role' => $tool->role
                ];
                $request->session()->put('activities', $activities);

                // redirect staff to their routes
                if ($tool->role == 'staff') {
                    return redirect('a/' . $tool->activity_id);
                }

                // redirect students to their routes (depending on activity format)
                return redirect('a/' . $tool->activity_id . '/' . $tool->format);
            } else {
                return view('lti.error');
            }
        } else {                // No cookies, show splash to force creation
            // todo: if failed to set cookie (i.e. would show splash twice)
            // show a link to open the tool in a new window instead
            // we put a key in the session which will still be present after 
            // the user submits the form, if the cookies have worked
            $request->session()->put('live', 1);
            return view('lti.splash')
            ->with('params', $request->input());
        }
    }
}
