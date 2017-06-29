<?php

namespace App\Reflect;

use Illuminate\Http\Request;

// helper functions
class Reflect
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function processAction()
    {
        $round = $this->request->route('round');
        $page = $this->request->route('page');

        if ($this->request->input('start') || $this->request->input('resume')) {

        }

        if ($this->request->input('next')) {

        }

        if ($this->request->input('prev')) {

        }

        if ($this->request->input('done')) {

        }
        // if start, generate a page based on current page / round

        // if next, find the next page and generate that

        // if prev, find the prev page

        // if done, generate the chart and return that
    }

}