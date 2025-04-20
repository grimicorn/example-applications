<?php

namespace App\Domain\Concerns\Supports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait HasEnumDisplayName
{
    public static function displayNameForKey($key): string
    {
        if (is_null($key)) {
            return '';
        }

        $name = self::customDisplayNames()->get($key, self::nameForKey($key));
        $name = str_replace('_', ' ', $name);
        $name = Str::title($name);

        return $name;
    }

    public static function customDisplayNames(): Collection
    {
        return collect([
            //
        ]);
    }

    abstract public static function nameForKey($key): string;
}
