<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class ActivityFormatComposer
{
    public function __construct() 
    {
        $this->reflect = app('Reflect');
    }

    public function compose(View $view)
    {
        $view->with('formats', $this->reflect->getFormats());
    }
}