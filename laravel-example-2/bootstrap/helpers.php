<?php
// Miscellaneous helper functions

if (!function_exists('r_collect')) {
    /**
     * Recursively collects a multi-dimensional array.
     *
     * @param  array $array
     *
     * @return Illuminate\Support\Collection
     */
    function r_collect($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = r_collect($value);
                $array[$key] = $value;
            }
        }
        return collect($array);
    }
}