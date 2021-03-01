<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_an_asset()
    {
        $clip = Clip::factory()->create();

        $asset = $clip->addAsset('mp4/uploadedfilepath');

        $this->assertCount(1, $clip->assets);

        $this->assertTrue($clip->assets->contains($asset));
    }
}
