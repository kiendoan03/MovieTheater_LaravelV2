<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->bothify('TKT-####-????')),
            'final_price' => $this->faker->randomElement([50000, 75000, 100000, 150000, 200000]),
            'customer_id' => null,
            'staff_id' => null,
        ];
    }
}
