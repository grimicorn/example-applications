<?php

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
