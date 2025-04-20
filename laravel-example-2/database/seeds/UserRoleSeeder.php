<?php

use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;
use App\Enums\UserPermissionEnum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(UserPermissionEnum::namesAndKeys())->each(function ($role) {
            Permission::firstOrCreate(['name' => $role]);
        });

        Role::firstOrCreate(['name' => UserRoleEnum::DEVELOPER])
            ->syncPermissions([
                UserPermissionEnum::CREATE_USER_ROLES,
                UserPermissionEnum::CREATE_USER_PERMISSIONS,
                UserPermissionEnum::EDIT_USER_ROLES,
                UserPermissionEnum::EDIT_USER_PERMISSIONS,
                UserPermissionEnum::MANAGE_USERS,
                UserPermissionEnum::VIEW_TELESCOPE,
                UserPermissionEnum::VIEW_NOVA,
                UserPermissionEnum::VIEW_HORIZON,
                UserPermissionEnum::VIEW_STYLE_GUIDE,
                UserPermissionEnum::MANAGE_JOBS,
            ]);

        Role::firstOrCreate(['name' => UserRoleEnum::SYSTEM])
            ->syncPermissions([
                UserPermissionEnum::MANAGE_JOBS,
            ]);


        Role::firstOrCreate(['name' => UserRoleEnum::ADMINISTRATOR])
            ->syncPermissions([
                UserPermissionEnum::MANAGE_USERS,
                UserPermissionEnum::VIEW_STYLE_GUIDE,
                UserPermissionEnum::MANAGE_JOBS,
            ]);
    }
}
