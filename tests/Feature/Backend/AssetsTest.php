<?php


namespace Tests\Feature\Backend;

use App\Jobs\ConvertVideoForStreaming;
use App\Mail\AssetsTransferred;
use App\Models\Asset;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private string $role = '';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('videos');

        $this->role = 'moderator';
    }

    /** @test */
    public function a_moderator_can_upload_a_video_file(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()])
            ->assertRedirect($clip->adminPath());

        $asset = $clip->assets()->first();

        $this->assertDatabaseHas('assets', ['path' => $asset->path]);

        Storage::disk('videos')->assertExists($asset->path);
    }

    /** @test */
    public function a_moderator_cannot_delete_a_not_owned_clip_asset(): void
    {
        $asset = Asset::factory()->create();

        $this->signInRole($this->role);

        $this->delete($asset->path())->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_delete_a_not_owned_clip_asset(): void
    {
        $asset = Asset::factory()->create();

        $this->signInRole('admin');

        $this->delete($asset->path());

        $this->assertDeleted($asset);
    }

    /** @test */
    public function a_moderator_can_delete_an_owned_clip_asset(): void
    {
        $clip = ClipFactory::withAssets(1)
            ->ownedBy($this->signInRole($this->role))
            ->create();

        $this->assertEquals(1, $clip->assets()->count());

        $this->delete($clip->assets->first()->path())
            ->assertRedirect($clip->adminPath());

        $this->assertEquals(0, $clip->assets()->count());
    }

    /** @test */
    public function deleting_an_asset_should_also_delete_the_file_from_storage(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()]);

        $asset = $clip->assets()->first();

        $this->assertDatabaseHas('assets', ['path' => $asset->path]);

        Storage::disk('videos')->assertExists($asset->path);

        $asset->delete();

        $this->assertDeleted($asset);

        Storage::disk('videos')->assertMissing($asset->path);
    }

    /** @test */
    public function an_asset_must_be_a_video_file(): void
    {
        $this->post(route('admin.assets.store', ClipFactory::ownedBy($this->signInRole($this->role))->create()), [
            'asset' => $file = UploadedFile::fake()->image('avatar.jpg')
        ])
            ->assertSessionHasErrors('asset');
    }

    /** @test */
    public function uploading_an_asset_should_save_asset_duration(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()]);

        $this->assertEquals(10,
            FFMpeg::fromDisk('videos')->open($clip->assets()->first()->path)->getDurationInSeconds());
    }

    /** @test */
    public function uploading_an_asset_should_create_a_clip_poster(): void
    {
        Storage::fake('thumbnails');

        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()]);

        $clip->refresh();

        Storage::disk('thumbnails')->assertExists($clip->posterImage);
    }

    /** @test */
    public function uploading_an_asset_should_notify_user_via_email(): void
    {
        Mail::fake();

        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()]);

        Mail::assertSent(AssetsTransferred::class);
    }

    /** @test */
    public function deleting_an_asset_should_delete_a_clip_poster(): void
    {
        Storage::fake('thumbnails');

        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()]);

        $clip->refresh();

        $this->delete($clip->assets()->first()->path());

        Storage::disk('thumbnails')->assertMissing($clip->posterImage);
    }

    /** @test */
    public function deleting_an_asset_should_update_clip_poster_image_column(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()]);

        $this->delete($clip->assets()->first()->path());

        $clip->refresh();

        $this->assertNull($clip->posterImage);
    }

    /** @test */
    public function it_converts_upload_file_to_hls(): void
    {
        Storage::fake('streamable_videos');

        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), [
            'asset'                 => FileFactory::videoFile(),
            'should_convert_to_hls' => 'on',
        ]);

        Storage::disk('streamable_videos')->assertExists($clip->assets()->first()->id . '.m3u8');

    }

    /** @test */
    public function it_should_queue_if_user_select_to_convert_to_hls(): void
    {
        Queue::fake();

        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->post(route('admin.assets.store', $clip), [
            'asset'                 => FileFactory::videoFile(),
            'should_convert_to_hls' => 'on',
        ]);

        Queue::assertPushed(ConvertVideoForStreaming::class);
    }
}
