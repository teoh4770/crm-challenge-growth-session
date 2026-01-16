<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $manageUsersPermission = Permission::create(['name' => 'manage users']);
        $manageClientsPermission = Permission::create(['name' => 'manage clients']);
        $manageProjectsPermission = Permission::create(['name' => 'manage projects']);
        $manageTasksPermission = Permission::create(['name' => 'manage tasks']);
        $viewOwnProjectsPermission = Permission::create(['name' => 'view own projects']);
        $viewOwnTasksPermission = Permission::create(['name' => 'view own tasks']);

        $adminRole->syncPermissions([
            $manageUsersPermission,
            $manageClientsPermission,
            $manageProjectsPermission,
            $manageTasksPermission,
            $viewOwnProjectsPermission,
            $viewOwnTasksPermission,
        ]);

        $userRole->syncPermissions([
            $viewOwnProjectsPermission,
            $viewOwnTasksPermission,
        ]);
    }
}
