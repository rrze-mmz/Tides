<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Facades\Tests\Setup\ClipFactory;
use Tests\TestCase;

class ClipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $clip = Clip::factory()->create();

        $this->assertEquals('/clips/'.$clip->slug, $clip->path());
    }

    /** @test */
    public function it_has_a_admin_path()
    {
        $clip = Clip::factory()->create();

        $this->assertEquals('/admin/clips/'.$clip->slug, $clip->adminPath());
    }

    /** @test */
    public function it_has_a_slug_route()
    {
        $clip = Clip::factory()->create();

        $this->get($clip->path())->assertStatus(200);
    }

    /** @test */
    public function a_slug_must_be_incremental()
    {
        $clipA = Clip::factory()->create(['title'=> 'A test title','slug'=> 'A test title']);
        $clipB = Clip::factory()->create(['title'=> 'A test title','slug'=> 'A test title']);

        $this->assertSame('a-test-title-2', $clipB->slug);
    }
    /** @test */
    public function slug_must_be_unique()
    {
        $clipA = Clip::factory()->create(['title'=> 'A test title','slug'=> 'A test title']);
        $clipB = Clip::factory()->create(['title'=> 'A test title','slug'=> 'A test title']);

        $this->assertNotEquals($clipA->slug, $clipB->slug);
    }

    /** @test */
    public function it_has_a_set_slug_fuction()
    {
        $clip = Clip::factory()->create();

        $this->assertEquals($clip->slug, Str::slug($clip->title));
    }
    /** @test */
    public function it_has_many_assets()
    {
        $clip = Clip::factory()->create();

        $assets = Asset::factory(2)->create(['clip_id'=> $clip->id]);

        $this->assertEquals(2, $clip->assets()->count());
    }

    /** @test */
    public function it_can_return_upload_date_in_carbon_format()
    {
        $clip = Clip::factory()->create(['updated_at'=>'2021-03-02 08:57:38']);

        $this->assertEquals('2021-03-02', $clip->updated_at);
    }

    /** @test */
    public function a_clip_has_only_one_owner()
    {
        $clip = ClipFactory::create();

        $this->assertInstanceOf(User::class, $clip->owner);
    }

    /** @test */
    public function it_can_add_an_asset()
    {
        $clip = Clip::factory()->create();

        $asset = $clip->addAsset('mp4/uploadedfilepath');

        $this->assertCount(1, $clip->assets);

        $this->assertTrue($clip->assets->contains($asset));
    }
}
