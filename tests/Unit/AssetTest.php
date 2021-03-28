<?php


namespace Tests\Unit;

use App\Events\AssetDeleted;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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

    /** @test */
    public function delete_an_asset_will_fire_an_event()
    {
        Event::fake();

        $asset = Asset::factory()->create();

        $asset->delete();

        Event::assertDispatched(AssetDeleted::class);
    }
}
