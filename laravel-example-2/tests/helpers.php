<?php
// Miscellaneous testing helper functions

if (!function_exists('create')) {
    function create(string $class, array $attributes = [], int $count = 1)
    {
        if ($count > 1) {
            return factory($class, $count)->create($attributes);
        }

        return factory($class)->create($attributes);
    }
}

if (!function_exists('make')) {
    function make(string $class, array $attributes = [], int $count = 1)
    {
        if ($count > 1) {
            return factory($class, $count)->make($attributes);
        }

        return factory($class)->make($attributes);
    }
}
