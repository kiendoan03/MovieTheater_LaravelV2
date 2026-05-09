<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

use function Symfony\Component\Translation\t;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule_id' => Schedule::inRandomOrder()->first()->id,
            'seat_id' => Seat::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement([0, 1, 2]), // 0: available, 1: booked, 2: reserved
            'ticket_id' => Ticket::inRandomOrder()->first()->id,
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'staff_id' => null,
        ];
    }
}
