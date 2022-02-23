<?php


namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClipTest extends TestCase
{
    use RefreshDatabase;

    protected Clip $clip;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clip = Clip::factory()->create();
    }

    /** @test */
    public function it_has_a_path(): void
    {
        $this->assertEquals('/clips/' . $this->clip->slug, $this->clip->path());
    }

    /** @test */
    public function it_has_a_admin_path(): void
    {
        $this->assertEquals('/admin/clips/' . $this->clip->slug, $this->clip->adminPath());
    }

    /** @test */
    public function it_has_a_slug_route(): void
    {
        $this->assertEquals('/clips/' . Str::slug($this->clip->title), $this->clip->path());
    }

    /** @test */
    public function it_has_an_incremental_slug(): void
    {
        Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $clip = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $this->assertSame('a-test-title-2', $clip->slug);
    }

    /** @test */
    public function it_has_a_unique_slug(): void
    {
        $clipA = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $clipB = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $this->assertNotEquals($clipA->slug, $clipB->slug);
    }

    /** @test */
    public function it_has_a_set_slug_function(): void
    {
        $this->assertEquals($this->clip->slug, Str::slug($this->clip->title));
    }

    /** @test */
    public function it_belongs_to_a_series(): void
    {
        $this->assertInstanceOf(BelongsTo::class, $this->clip->series());
    }

    /** @test */
    public function it_has_many_assets(): void
    {
        Asset::factory(2)->create(['clip_id' => $this->clip->id]);

        $this->assertEquals(2, $this->clip->assets()->count());
    }

    /** @test */
    public function it_has_only_one_semester(): void
    {
        $this->assertInstanceOf(BelongsTo::class, $this->clip->semester());
    }

    /** @test */
    public function it_has_one_organization_unit(): void
    {
        $this->assertInstanceOf(HasOne::class, $this->clip->organisation());
    }

    /** @test */
    public function it_has_one_language(): void
    {
        $this->assertInstanceOf(HasOne::class, $this->clip->language());
    }

    /** @test */
    public function it_has_one_context(): void
    {
        $this->assertInstanceOf(HasOne::class, $this->clip->context());
    }

    /** @test */
    public function it_has_one_format(): void
    {
        $this->assertInstanceOf(HasOne::class, $this->clip->format());
    }

    /** @test */
    public function it_has_one_type(): void
    {
        $this->assertInstanceOf(HasOne::class, $this->clip->type());
    }

    /** @test */
    public function it_has_many_comments(): void
    {
        Comment::factory(2)->create(['clip_id' => $this->clip->id]);

        $this->assertEquals(2, $this->clip->comments()->count());
    }

    /** @test */
    public function it_belongs_to_an_owner(): void
    {
        $this->assertInstanceOf(User::class, $this->clip->owner);
    }

    /** @test */
    public function it_has_many_presenters_using_presentable_trait(): void
    {
        $this->assertInstanceOf(MorphToMany::class, $this->clip->presenters());
    }

    /** @test */
    public function it_can_add_an_asset(): void
    {
        $asset = $this->clip->addAsset([
            'disk'               => 'videos',
            'original_file_name' => 'video.mp4',
            'path'               => '/videos/',
            'duration'           => '100',
            'width'              => '1920',
            'height'             => '1080',
            'type'               => 'video',
        ]);

        $this->assertInstanceOf(Asset::class, $asset);
    }

    /** @test */
    public function it_can_updates_its_poster_image(): void
    {
        $this->assertNull($this->clip->posterImage);

        $file = FileFactory::videoFile();

        $file->storeAs('thumbnails', $this->clip->id . '_poster.png');

        $this->clip->updatePosterImage();

        $this->assertEquals('1_poster.png', $this->clip->posterImage);

        Storage::disk('thumbnails')->delete($this->clip->id . '_poster.png');
    }

    /** @test */
    public function it_can_add_tags(): void
    {
        $this->clip->addTags(collect(['php', 'tides']));

        $this->assertEquals(2, $this->clip->tags()->count());
    }

    /** @test */
    public function it_can_fetch_a_collection_of_previous_and_next_clip_models_if_clip_belongs_to_a_series(): void
    {
        $series = Series::factory()->create();

        Clip::factory()->create([
            'title'     => 'first clip',
            'series_id' => $series->id,
            'episode'   => 1,
        ]);

        $secondClip = Clip::factory()->create([
            'title'     => 'second clip',
            'series_id' => $series->id,
            'episode'   => 2,
        ]);

        Clip::factory()->create([
            'title'     => 'third clip',
            'series_id' => $series->id,
            'episode'   => 3,
        ]);

        $this->assertInstanceOf(Collection::class, $secondClip->previousNextClipCollection());
        $this->assertInstanceOf(Clip::class, $secondClip->previousNextClipCollection()->get('previousClip'));
        $this->assertInstanceOf(Clip::class, $secondClip->previousNextClipCollection()->get('nextClip'));
    }

    /** @test */
    public function it_an_asset_instance_if_clip_has_smil_file(): void
    {
        $this->assertNull($this->clip->getCameraSmil());

        $this->clip->addAsset([
            'disk'               => 'videos',
            'original_file_name' => 'camera.smil',
            'type'               => 'smil',
            'path'               => '/videos/camera.smil',
            'duration'           => '0',
            'width'              => '0',
            'height'             => '0',
        ]);

        $this->assertInstanceOf(Asset::class, $this->clip->getCameraSmil());
    }

    /** @test */
    public function it_has_a_public_scope(): void
    {
        $this->assertInstanceOf(Builder::class, Clip::public());
    }

    /** @test */
    public function clip_owner_can_be_null(): void
    {
        $user = User::factory()->create();

        $clip = $user->clips()->create(['title' => 'test', 'slug' => 'test', 'semester_id' => 1]);

        $user->delete();

        $clip = Clip::find($clip->id);

        $this->assertNull($clip->owner_id);
    }

    /** @test */
    public function it_resolve_also_id_in_route(): void
    {
        $this->get('clips/' . $this->clip->id)->assertStatus(403);
        $this->get(route('frontend.clips.show', $this->clip))->assertStatus(403);
        $this->get('clips/291')->assertStatus(404);
    }

    /** @test */
    public function it_fetches_assets_by_type(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->clip->getAssetsByType('presenter'));
    }
}
