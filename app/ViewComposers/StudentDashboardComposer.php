<?php

namespace App\ViewComposers;

use App\Reflect\MessageHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardComposer
{
    protected $activity;

    public function __construct(Request $request) 
    {
        $this->activity = $request->route('activity');
    }

    /**
     * Composes view with a philosophical quote about knowledge.
     * @return view
     */
    public function compose(View $view)
    {
        $knowledgeQuote = MessageHelper::getPhilosophicalMessage();

        $view->with('knowledgeQuote', $knowledgeQuote);
    }
}