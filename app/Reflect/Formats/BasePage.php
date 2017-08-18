<?php

namespace App\Reflect\Formats;

use Illuminate\Http\Request;
use stdClass;

class BasePage
{
    protected $request;

    protected $actions = [
        'next' => 'next',
        'prev' => 'prev',
        'done' => 'done',
        'resume' => 'resume',
        'default' => 'default'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Redirects the user to the activity home if action isn't understood.
     *
     */
    public function default()
    {
        return redirect('a/' . $this->request->route('activity')->id);
    }

    /**
     * Checks the HTTP POST request for an action present in either $this->actions
     * or subclass' $formatActions
     *
     */
    public function getAction()
    {
        if (isset($this->formatActions)) {
            $this->actions = array_merge($this->actions, $this->formatActions);
        }

        $actionObj = new stdClass();

        foreach ($this->actions as $action => $actionMethod) {
            if ($this->request->input($action)) {
                $actionObj->action = $action;
                $actionObj->param = $this->request->input($action);

                return $actionObj;
            }
        }

        $actionObj->action = 'default';
        $actionObj->param = '';
        return $actionObj;
    }

}