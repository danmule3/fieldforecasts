<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Permission list is scoped to what Module 1 (Auth/Users) needs.
     * Later modules (Predictions, Subscriptions, Admin panel, Blog)
     * append their own permission groups here rather than replacing
     * this seeder, so re-running it stays idempotent and additive.
     */
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.update',
            'users.delete',
            'roles.manage',
            'activity-logs.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $registered = Role::firstOrCreate(['name' => User::ROLE_REGISTERED, 'guard_name' => 'web']);

        $premium = Role::firstOrCreate(['name' => User::ROLE_PREMIUM, 'guard_name' => 'web']);

        $editor = Role::firstOrCreate(['name' => User::ROLE_EDITOR, 'guard_name' => 'web']);

        $admin = Role::firstOrCreate(['name' => User::ROLE_ADMIN, 'guard_name' => 'web']);
        $admin->syncPermissions(['users.view', 'users.update', 'activity-logs.view']);

        $superAdmin = Role::firstOrCreate(['name' => User::ROLE_SUPER_ADMIN, 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());
    }
}
