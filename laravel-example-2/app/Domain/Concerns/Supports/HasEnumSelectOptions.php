<?php

namespace App\Domain\Concerns\Supports;

use Illuminate\Support\Str;

trait HasEnumSelectOptions
{
    public static function novaFieldSelectOptions()
    {
        return collect(static::namesAndKeys())->mapWithKeys(function ($value, $key) {
            $label = str_replace('_', ' ', $key);
            $label = Str::title($label);
            return [$label => $value];
        })->flip()->toArray();
    }

    public static function novaFilterSelectOptions()
    {
        return collect(self::novaFieldSelectOptions())->flip()->toArray();
    }
}
