<?php

namespace Database\Factories;
use App\Models\Room;
use App\Models\SeatType;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'row' => $this->faker->numberBetween(1, 15),
            'column' => $this->faker->numberBetween(1, 20),
            'room_id' => Room::inRandomOrder()->first()->id,
            'type_id' => SeatType::inRandomOrder()->first()->id,
        ];
    }
}
