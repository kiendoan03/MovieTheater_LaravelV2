<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RoomTypeSeeder::class,
            RoomSeeder::class,
            SeatTypeSeeder::class,
            SeatSeeder::class,

            CategorySeeder::class,
            ActorSeeder::class,
            DirectorSeeder::class,
            MovieSeeder::class,

            ScheduleSeeder::class,

            AccountSeeder::class,   // admin cố định
            CustomerSeeder::class,  // tự tạo account customer qua factory
            StaffSeeder::class,     // tự tạo account staff qua factory

            BookingSeeder::class,

            RefreshTokenSeeder::class, // sau cùng, khi đã có đủ accounts
        ]);
    }
}
