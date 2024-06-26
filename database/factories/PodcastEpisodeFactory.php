<?php

namespace Database\Factories;

use App\Models\Podcast;
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
            'podcast_id' => Podcast::factory(),
            'episode_number' => $this->faker->randomDigit(),
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'audio_url' => $this->faker->url(),
            'notes' => $this->faker->paragraph(),
            'transcription' => $this->faker->paragraph(),
            'spotify_url' => $this->faker->url(),
            'apple_podcasts_url' => $this->faker->url(),
            'google_podcasts_url' => $this->faker->url(),
        ];
    }
}
