<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Booking;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = Schedule::all();

    foreach ($schedules as $schedule) {

        // 1. tạo booking trống (available)
        $seats = Seat::where('room_id', $schedule->room_id)->get();

        foreach ($seats as $seat) {
            Booking::create([
                'schedule_id' => $schedule->id,
                'seat_id' => $seat->id,
                'status' => 0
            ]);
        }

        // 2. giả lập user chọn ghế
        $selected = Booking::where('schedule_id', $schedule->id)
            ->inRandomOrder()
            ->take(5)
            ->get();

        foreach ($selected as $booking) {
            $booking->update([
                'status' => 1,
                'customer_id' => Customer::inRandomOrder()->value('id')
            ]);
        }

        // 3. giả lập thanh toán → tạo ticket
        $ticket = Ticket::create([
            'code' => 'TICKET-' . rand(1000,9999),
            'final_price' => 0
        ]);

        foreach ($selected as $booking) {
            $booking->update([
                'status' => 3,
                'ticket_id' => $ticket->id
            ]);
        }
    }

    }
}
