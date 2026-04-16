<?php

namespace Database\Factories;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password123',
            'role'     => UserRole::Customer,
            'is_active'=> true,
        ];
    }

    /** Account dành cho staff */
    public function staff(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => UserRole::Staff,
        ]);
    }

    /** Account dành cho admin */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => UserRole::Admin,
        ]);
    }

    /** Account dành cho customer (default) */
    public function customer(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => UserRole::Customer,
        ]);
    }
}
