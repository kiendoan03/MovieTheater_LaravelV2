<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->name(),
            'email'         => $this->faker->unique()->safeEmail(),
            'phonenumber'   => $this->faker->numerify('09########'),
            'address'       => $this->faker->address(),
            'avatar'        => $this->faker->imageUrl(200, 200, 'people'),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'account_id'    => Account::factory()->staff(),
        ];
    }
}

