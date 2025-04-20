<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The MachineStatus enum.
 *
 * @method static self RUNNING()
 * @method static self DOWN()
 */
class MachineStatus extends Enum
{
    use HasEnumSelectOptions;

    const IMPORTED = 0;
    const RUNNING = 1;
    const DOWN = 2;
}
