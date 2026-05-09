<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\RefreshToken;
use Illuminate\Database\Seeder;

class RefreshTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Tạo refresh token cho từng account đã có trong DB.
     */
    public function run(): void
    {
        Account::all()->each(function (Account $account) {
            // 1 active token cho mỗi account
            RefreshToken::factory()->create([
                'account_id' => $account->id,
            ]);

            // 50% chance: thêm 1 revoked token (giả lập lịch sử đăng nhập cũ)
            if (rand(0, 1)) {
                RefreshToken::factory()->revoked()->create([
                    'account_id' => $account->id,
                ]);
            }
        });
    }
}
