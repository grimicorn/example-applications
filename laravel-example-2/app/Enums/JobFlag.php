<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumDisplayName;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The JobFlag enum.
 */
class JobFlag extends Enum
{
    use HasEnumDisplayName,
        HasEnumSelectOptions;

    const HOT_MARKET = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;
}
