<?php

namespace App\Enums;

use App\Domain\Concerns\Supports\HasEnumDisplayName;
use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The WipStatus enum.
 *
 */
class WipStatus extends Enum
{
    use HasEnumSelectOptions,
        HasEnumDisplayName;

    const A = 'A';
    const B = 'B';
    const C = 'C';
    const D = 'D';
    const E = 'E';
    const F = 'F';
    const G = 'G';
    const H = 'H';
    const J = 'J';
    const K = 'K';
    const Q = 'Q';
    const R = 'R';
    const X = 'X';
}
