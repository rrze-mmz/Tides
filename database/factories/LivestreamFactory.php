<?php

namespace Database\Factories;

use App\Models\Livestream;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livestream>
 */
class LivestreamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->title,
            'url' => $this->faker->url,
        ];
    }
}
