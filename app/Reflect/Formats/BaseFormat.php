<?php

namespace App\Reflect\Formats;

use Illuminate\Http\Request;

class BaseFormat
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

    public function getAction()
    {
        foreach ($this->actions as $action => $actionMethod) {
            if ($this->request->input($action)) {
                return $actionMethod;
            }
        }

        return $this->actions['default'];
    }

    /**
     * Redirects the user to the activity home if action isn't understood.
     *
     */
    public function default()
    {
        return redirect('a/' . $this->request->route('activity')->id);
    }
}