<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumDisplayName;

/**
 * The Placement enum.
 */
class Placement extends Enum
{
    use HasEnumDisplayName;

    const UNKNOWN = 'unknown';
    const FRONT = 'front';
    const BACK = 'back';
    const LEFT_CHEST = 'left chest';
    const RIGHT_CHEST = 'right chest';
    const LEFT_SLEEVE = 'left sleeve';
    const RIGHT_SLEEVE = 'right sleeve';
    const LEFT_SHORT_SLEEVE = 'left short sleeve';
    const RIGHT_SHORT_SLEEVE = 'right short sleeve';
    const LEFT_LONG_SLEEVE = 'left long sleeve';
    const RIGHT_LONG_SLEEVE = 'right long sleeve';
    const LEFT_THIGH = 'left thigh';
    const RIGHT_THIGH = 'right thigh';
    const FOIL = 'foil';
    const CUSTOM = 'custom';
}
