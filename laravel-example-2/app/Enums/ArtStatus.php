<?php

namespace App\Enums;

use App\Domain\Concerns\Supports\HasEnumDisplayName;
use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The WipStatus enum.
 *
 */
class ArtStatus extends Enum
{
    use HasEnumSelectOptions,
        HasEnumDisplayName;

    const A = 'A';
    const C = 'C';
    const S = 'S';
}
