<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['2D', '3D', 'IMAX', '4DX', 'Dolby Cinema'];
        
        return [
            'type' => $this->faker->randomElement($types),
            'capacity' => $this->faker->randomElement([50, 75, 100, 150, 200]),
        ];
    }
}
