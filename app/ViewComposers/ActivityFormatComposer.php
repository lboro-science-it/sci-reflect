<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class ActivityFormatComposer
{
    public function __construct() 
    {
        $this->reflect = app('Reflect');
    }

    /**
     * Composes view with a HTML-form friendly list of the available formats.
     * @return view
     */
    public function compose(View $view)
    {
        $view->with('formats', $this->reflect->getFormatDisplayNames());
    }
}