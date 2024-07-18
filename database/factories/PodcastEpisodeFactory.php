<?php

namespace Database\Factories;

use App\Models\PodcastEpisode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PodcastEpisode>
 */
class PodcastEpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'podcast_id' => 1,
            'episode_number' => $this->faker->randomDigit(),
            'title' => $this->faker->sentence(),
            'recording_date' => $this->faker->dateTime()->format('d-m-Y H:i:s'),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'image_id' => null,
            'is_published' => true,
            'notes' => $this->faker->paragraph(),
            'transcription' => $this->faker->paragraph(),
            'website_url' => $this->faker->url(),
            'spotify_url' => $this->faker->url(),
            'apple_podcasts_url' => $this->faker->url(),
            'old_episode_id' => $this->faker->numberBetween(100, 300),
        ];
    }
}
