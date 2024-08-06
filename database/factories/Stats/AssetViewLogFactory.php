<?php

namespace Database\Factories\Stats;

use App\Enums\Acl;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<VideoViewLog>
 */
class AssetViewLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => $assetID = Asset::factory()->create()->id,
            'service_id' => Acl::PORTAL,
            'access_date' => Carbon::now()->toDate(),
            'access_time' => Carbon::now(),
            'remote_addr' => $this->faker->ipv4,
            'remote_host' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)',
            'remote_user' => 'GET',
            'script_name' => '/get/player'.$assetID,
            'is_counted' => true,
            'created_at' => Carbon::now(),
            'is_valid' => true,
            'in_range' => false,
            'referer' => 'localhost/clip/testClip-1',
            'query' => '',
            'is_akami' => false,
            'server' => env('app_env'),
            'range' => '',
            'response' => '200 OK',
            'real_ip' => $this->faker->ipv4,
            'num_ip' => '1293489108',
            'last_modified_at' => Carbon::now(),
            'last_modified_from' => '',
            'bot_name' => null,
            'city' => $this->faker->city,
            'country' => $this->faker->countryCode,
            'counter3' => '',
            'is_get' => false,
            'is_bot' => false,
            'region' => 'BY',
            'region_name' => 'Bavaria',
        ];
    }
}
