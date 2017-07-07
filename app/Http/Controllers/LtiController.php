<?php

namespace App\Http\Controllers;

use App\Lti\LtiToolProvider;
use Auth;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

                $now = new DateTime();
                Log::info('LTI Launch Failure: ' . $now->format('Y-m-d H:i:s'));
                Log::info($tool->reason);
                Log::info(json_encode($request));
                Log::info(json_encode($tool));
                return view('lti.error');
            }
        } else {                // No cookies, show splash to force creation
            $request->session()->put('live', 1);
            return view('lti.splash')
            ->with('params', $request->input());
        }
    }
}
