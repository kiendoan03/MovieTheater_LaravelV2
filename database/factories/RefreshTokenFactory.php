<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RefreshToken>
 */
class RefreshTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token'             => Str::random(64),
            'expires_at'        => now()->addDays(7),
            'is_revoked'        => false,
            'revoked_at'        => null,
            'replaced_by_token' => null,
            'account_id'        => Account::factory(),
        ];
    }

    /** Token đã bị thu hồi */
    public function revoked(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_revoked' => true,
            'revoked_at' => now(),
        ]);
    }

    /** Token đã hết hạn */
    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => now()->subDays(1),
        ]);
    }
}
