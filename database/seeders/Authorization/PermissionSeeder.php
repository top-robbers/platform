<?php

namespace Database\Seeders\Authorization;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'platform.access_admin_panel',

            'platform.accounts.view',
            'platform.accounts.create',
            'platform.accounts.update',
            'platform.accounts.delete',

            'platform.roles.view',
            'platform.roles.create',
            'platform.roles.update',
            'platform.roles.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}