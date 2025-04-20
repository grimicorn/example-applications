<?php

namespace App\Enums;

use App\Domain\Concerns\Supports\HasEnumDisplayName;
use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The WipStatus enum.
 *
 */
class PickStatus extends Enum
{
    use HasEnumSelectOptions,
        HasEnumDisplayName;

    const H = 'H';
    const J = 'J';
    const N = 'N';
}
