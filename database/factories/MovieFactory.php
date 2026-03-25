<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $releaseDate = $this->faker->dateTimeBetween('-2 years', 'now');
        
        return [
            'movie_name' => $this->faker->sentence(3, true),
            'logo' => $this->faker->imageUrl(200, 300, 'cinema'),
            'poster' => $this->faker->imageUrl(400, 600, 'cinema'),
            'thumbnail' => $this->faker->imageUrl(300, 400, 'cinema'),
            'rating' => $this->faker->randomFloat(1, 1, 10),
            'synopsis' => $this->faker->paragraphs(3, true),
            'language' => $this->faker->randomElement(['English', 'Vietnamese', 'Chinese', 'Japanese', 'Korean']),
            'country' => $this->faker->randomElement(['USA', 'Vietnam', 'China', 'Japan', 'South Korea', 'France', 'UK']),
            'length' => $this->faker->numberBetween(90, 180),
            'release_date' => $releaseDate,
            'end_date' => $this->faker->dateTimeBetween($releaseDate, '+3 months'),
            'age_restricted' => $this->faker->randomElement([0, 13, 16, 18]),
            'trailer' => 'https://www.youtube.com/embed/' . $this->faker->regexify('[a-zA-Z0-9]{11}'),
        ];
    }
}
