<?php

namespace Database\Factories;

use App\Enums\Acl;
use App\Models\Asset;
use App\Models\StatsCounter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<StatsCounter>
 */
class StatsCounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'version' => '0',
            'counter' => '20',
            'doa' => Carbon::now(),
            'resourceid' => Asset::factory()->create()->id,
            'serviceid' => Acl::PORTAL,
        ];
    }
}
