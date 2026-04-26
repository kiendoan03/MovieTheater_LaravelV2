<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeatType>
 */
class SeatTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['Standard', 'VIP', 'Premium', 'Couple']);
        return [
            'type' => $type,
            'price' => $this->faker->randomElement([50000, 75000, 100000, 150000]),
            'color' => match ($type) {
                'Standard' => '#3b82f6',
                'VIP' => '#f59e0b',
                'Premium' => '#8b5cf6',
                'Couple' => '#ec4899',
                default => '#ffffff'
            },
            'is_couple' => $type === 'Couple',
        ];
    }
}
