<?php

namespace Database\Factories\Stats;

use App\Enums\Acl;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Stats\AssetViewCount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<AssetViewCount>
 */
class AssetViewCountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $asset = Asset::factory()->create();
        $clip = Clip::Factory()->create();
        $clip->addAsset($asset);

        return [
            'version' => '0',
            'counter' => '20',
            'doa' => Carbon::now(),
            'resourceid' => $asset->id,
            'serviceid' => Acl::PORTAL,
        ];
    }
}
