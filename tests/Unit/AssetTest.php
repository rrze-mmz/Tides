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
    public function it_has_a_path()
    {
        $asset = Asset::factory()->create();

        $this->assertEquals('/admin/assets/'.$asset->id, $asset->path());
    }
}
