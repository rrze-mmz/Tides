<?php

namespace Database\Factories;

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'is_published' => true,
            'website_url' => 'https://www.podcasts.com/tides-podcast',
            'spotify_url' => 'https://www.spotify.com/podcast/tides-podcast',
            'apple_podcasts_url' => 'https://www.apple.podcasts.com/tides-podcast',
            'old_podcast_id' => $this->faker->numberBetween(100, 300),
            'owner_id' => User::factory()->create()->id,
        ];
    }
}
