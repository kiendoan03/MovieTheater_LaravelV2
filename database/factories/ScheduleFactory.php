<?php

namespace Database\Factories;
use App\Models\Room;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+30 days');
        $startTime = $this->faker->randomElement(['08:00', '11:00', '14:00', '17:00', '20:00', '23:00']);
        $endTime = $this->faker->randomElement(['10:30', '13:30', '16:30', '19:30', '22:30', '01:30']);
        
        return [
            'date' => $startDate->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room_id' => Room::inRandomOrder()->first()->id,
            'movie_id' => Movie::inRandomOrder()->first()->id,
        ];
    }
}
