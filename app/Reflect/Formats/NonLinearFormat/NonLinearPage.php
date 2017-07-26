<?php

namespace App\Reflect\Formats\NonLinearFormat;

use App\Reflect\Formats\BasePage;
use Illuminate\Http\Request;

class NonLinearPage extends BasePage
{
    protected $request;

    protected $formatActions = [
        'page' => 'page'
    ];

    protected $view = 'page.nonlinear.show';

    /**
     * Merges format-specific actions with base actions.
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function processPage()
    {
        // insert stuff for handling a page in nonlinear format
    }

}