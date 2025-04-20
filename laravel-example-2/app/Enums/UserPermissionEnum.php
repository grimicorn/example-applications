<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;
use App\Domain\Concerns\Supports\HasEnumSelectOptions;

/**
 * The UserPermissionEnum enum.
 *
 * @method static self
 */
class UserPermissionEnum extends Enum
{
    use HasEnumSelectOptions;

    const CREATE_USER_ROLES = 'create user roles';
    const CREATE_USER_PERMISSIONS = 'create user permissions';
    const EDIT_USER_ROLES = 'edit user roles';
    const EDIT_USER_PERMISSIONS = 'edit user permissions';
    const MANAGE_USERS = 'manage users';
    const VIEW_TELESCOPE = 'view telescope';
    const VIEW_NOVA = 'view nova';
    const VIEW_HORIZON = 'view horizon';
    const VIEW_STYLE_GUIDE = 'view style guide';
    const MANAGE_JOBS = 'manage jobs';
}
