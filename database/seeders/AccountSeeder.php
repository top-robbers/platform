<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Account::updateOrCreate(
            ['email' => 'admin@top-robbers.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'active' => true
            ],
        );

        $admin->syncRoles(['admin']);

        $player = Account::updateOrCreate(
            ['email' => 'player@top-robbers.test'],
            [
                'name' => 'Player',
                'password' => Hash::make('player'),
                'active' => true
            ],
        );

        $player->syncRoles(['player']);
    }
}