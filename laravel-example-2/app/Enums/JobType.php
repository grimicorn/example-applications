<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The JobType enum.
 *
 * @method static self SETUP()
 * @method static self MULTI_HIT()
 * @method static self SAMPLE()
 */
class JobType extends Enum
{
    use HasEnumSelectOptions;

    const DEFAULT = 1;
    const SETUP = 2;
    const MULTI_HIT = 3;
    const SAMPLE = 4;
}
