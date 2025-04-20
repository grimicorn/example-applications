<?php

namespace App\Support;

class JavascriptVars
{
    public function get()
    {
        return collect([
            'user' => auth()->user()->load('sites'),
        ]);
    }
}
