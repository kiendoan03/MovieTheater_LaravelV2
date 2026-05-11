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
            'email' => 'admin@gmail.com',
            'password' => 'Abc@123456',   // model tự hash qua 'hashed' cast
            'role' => UserRole::Admin,
        ]);

        Account::create([
            'email' => 'staff@gmail.com',
            'password' => 'Abc@123456',
            'role' => UserRole::Staff,
        ]);
    }
}
