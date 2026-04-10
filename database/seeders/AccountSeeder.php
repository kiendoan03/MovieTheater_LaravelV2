<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::create([
            'username' => 'admin',
            'password' => 'password123',   // model tự hash qua 'hashed' cast
            'role'     => UserRole::Admin,
        ]);
    }
}
