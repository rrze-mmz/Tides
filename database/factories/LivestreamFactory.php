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
            'clip_id' => null,
            'name' => 'test-lecture-hall',
            'url' => 'wowza_livestream_url',
            'content_path' => 'wowza_content_path',
            'file_path' => 'wowza_file_path',
            'active' => false,
            'app_name' => 'test-lecture-hall',
            'opencast_location_name' => 'test-lecture-hall',
        ];
    }
}
