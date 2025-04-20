<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The RoleEnum enum.
 *
 * @method static self
 */
class UserRoleEnum extends Enum
{
    use HasEnumSelectOptions;

    const ADMINISTRATOR = 'administrator';
    const DEVELOPER = 'developer';
    const SYSTEM = 'system';
}
