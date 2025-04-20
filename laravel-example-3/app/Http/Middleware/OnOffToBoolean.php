<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class OnOffToBoolean extends TransformsRequest
{
     /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (!is_string($value) or (strtolower($value) !== 'on' and strtolower($value) !== 'off')) {
            return $value;
        }

        return (strtolower($value) === 'on');
    }
}
