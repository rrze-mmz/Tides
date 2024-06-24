<?php

namespace Database\Factories;

use App\Models\Podcast;
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
            'title' => 'Tides Podcast',
            'slug' => 'tides-podcast',
            'description' => 'This is a test podcast',
            'cover_image_url' => 'podcast-cover.png',
            'is_published' => true,
            'website_url' => 'https:///www.podcasts.com/tides-podcast',
            'spotify_url' => 'https://www.spotify.com/podcast/tides-podcast',
            'apple_podcasts_url' => 'https://www.apple.podcasts.com/tides-podcast',
            'google_podcasts_url' => 'https://www.google.com/podcast/tides-podcast',
        ];
    }
}
