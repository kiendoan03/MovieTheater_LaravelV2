<?php

namespace Database\Factories;
use App\Models\RoomType;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_name' => 'Room ' . $this->faker->unique()->numberBetween(1, 100),
            'type_id' => RoomType::inRandomOrder()->first()->id,
            'capacity' => $this->faker->randomElement([50, 70, 100, 150, 200]),
        ];
    }
}
