<?php

namespace App\Reflect;

use Auth;

class BlockContentParser {
    protected $openTag = '{$';

    protected $closeTag = '$}';

    protected $tags = [];

    public function __construct()
    {
        $this->tags = [
            'username' => Auth::user()->name,
        ];
    }

    public function parse($content)
    {
        foreach ($this->tags as $tag => $value) {
            $tagStr = $this->openTag . $tag . $this->closeTag;
            $content = str_replace($tagStr, $value, $content);
        }

        return $content;
    }
}
