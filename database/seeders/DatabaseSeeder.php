<?php

namespace Database\Seeders;

use Database\Seeders\Authorization\PermissionSeeder;
use Database\Seeders\Authorization\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AccountSeeder::class,
        ]);
    }
}