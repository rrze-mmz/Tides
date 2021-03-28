<?php


namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use Carbon\Carbon;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
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
    public function it_has_an_incremental_slug()
    {
        Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $clip = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $this->assertSame('a-test-title-2', $clip->slug);
    }

    /** @test */
    public function it_has_a_unique_slug()
    {
        $clipA = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $clipB = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $this->assertNotEquals($clipA->slug, $clipB->slug);
    }

    /** @test */
    public function it_has_a_set_slug_function()
    {
        $clip = Clip::factory()->create();

        $this->assertEquals($clip->slug, Str::slug($clip->title));
    }

    /** @test */
    public function it_has_many_assets()
    {
        $clip = Clip::factory()->create();

        Asset::factory(2)->create(['clip_id' => $clip->id]);

        $this->assertEquals(2, $clip->assets()->count());
    }

    /** @test */
    public function it_can_return_created_date_in_carbon_format()
    {
        $clip = Clip::factory()->create(['created_at' => '2021-03-02 08:57:38']);

        $this->assertEquals('2021-03-02', $clip->created_at);
    }

    /** @test */
    public function it_has_only_one_owner()
    {
        $clip = ClipFactory::create();

        $this->assertInstanceOf(User::class, $clip->owner);
    }

    /** @test */
    public function it_can_add_an_asset()
    {
        Storage::fake('videos');

        $clip = Clip::factory()->create();

        $clipStoragePath = getClipStoragePath($clip);
        $fileNameDate = Carbon::createFromFormat('Y-m-d', $clip->created_at)->format('Ymd');

        $videoFile = FileFactory::videoFile();

        $asset = $clip->addAsset([
            'disk'               => 'videos',
            'original_file_name' => $videoFile->getClientOriginalName(),
            'path'               =>
                $path = $videoFile->storeAs($clipStoragePath,
                    $fileNameDate.'-'.$clip->slug.'.'.Str::of($videoFile->getClientOriginalName())->after('.'),
                    'videos'),
            'duration'           => FFMpeg::fromDisk('videos')->open($path)->getDurationInSeconds(),
            'width'              => FFMpeg::fromDisk('videos')->open($path)->getVideoStream()->getDimensions()->getWidth(),
            'height'             => FFMpeg::fromDisk('videos')->open($path)->getVideoStream()->getDimensions()->getHeight()
        ]);

        $this->assertCount(1, $clip->assets);

        $this->assertTrue($clip->assets->contains($asset));

        $asset->delete();
    }

    /** @test */
    public function it_can_updates_its_poster_image()
    {
        $clip = Clip::factory()->create();

        $this->assertNull($clip->posterImage);

        $file = FileFactory::videoFile();

        $file->storeAs('thumbnails', $clip->id.'_poster.png');

        $clip->updatePosterImage();

        $this->assertEquals('1_poster.png', $clip->posterImage);

        Storage::disk('thumbnails')->delete($clip->id.'_poster.png');
    }

    /** @test */
    public function it_can_add_tags()
    {
        $clip = Clip::factory()->create();

        $clip->addTags(['php', 'tides']);

        $this->assertEquals(2, $clip->tags()->count());
    }
}
