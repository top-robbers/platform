<?php

namespace Database\Seeders\Authorization;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // todo
        $admin = Role::findOrCreate('admin', 'web');
        $moderator = Role::findOrCreate('moderator', 'web');
        $helper = Role::findOrCreate('helper', 'web');
        $player = Role::findOrCreate('player', 'web');

        $admin->syncPermissions(Permission::all());

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}