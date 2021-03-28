<?php


namespace Tests\Feature\Backend;

use App\Jobs\ConvertVideoForStreaming;
use App\Mail\VideoUploaded;
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

    use  RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('videos');
    }

    /** @test */
    public function an_authenticated_user_can_upload_a_video_file()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()])
            ->assertRedirect($clip->adminPath());

        $asset = $clip->assets()->first();

        $this->assertDatabaseHas('assets', ['path' => $asset->path]);

        Storage::disk('videos')->assertExists($asset->path);
    }

    /** @test */
    public function an_authenticated_user_cannot_delete_a_not_owned_clip_asset()
    {
        $asset = Asset::factory()->create();

        $this->signIn();

        $this->delete($asset->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_delete_an_owned_clip_asset()
    {
        $clip = ClipFactory::withAssets(1)
            ->ownedBy($this->signIn())
            ->create();

        $this->assertEquals(1, $clip->assets()->count());

        $this->delete($clip->assets->first()->path())
            ->assertRedirect($clip->adminPath());

        $this->assertEquals(0, $clip->assets()->count());
    }

    /** @test */
    public function deleting_an_asset_should_also_delete_the_file_from_storage()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()]);

        $asset = $clip->assets()->first();

        $this->assertDatabaseHas('assets', ['path' => $asset->path]);

        $asset->delete();

        $this->assertDeleted($asset);

        Storage::disk('videos')->assertMissing($asset->path);
    }

    /** @test */
    public function an_asset_must_be_a_video_file()
    {
        $this->post(route('admin.assets.store', ClipFactory::ownedBy($this->signIn())->create()), [
            'asset' => $file = UploadedFile::fake()->image('avatar.jpg')
        ])
            ->assertSessionHasErrors('asset');
    }

    /** @test */
    public function uploading_an_asset_should_save_asset_duration()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()]);

        $this->assertEquals(10,
            FFMpeg::fromDisk('videos')->open($clip->assets()->first()->path)->getDurationInSeconds());

    }

    /** @test */
    public function uploading_an_asset_should_create_a_clip_poster()
    {
        Storage::fake('thumbnails');

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()]);

        $clip->refresh();

        Storage::disk('thumbnails')->assertExists($clip->posterImage);
    }

    /** @test */
    public function uploading_an_asset_should_notify_user_via_email()
    {
        Mail::fake();

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()]);

        Mail::assertSent(VideoUploaded::class);
    }

    /** @test */
    public function deleting_an_asset_should_delete_a_clip_poster()
    {
        Storage::fake('thumbnails');

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()]);

        $clip->refresh();

        $this->delete($clip->assets()->first()->path());

        Storage::disk('thumbnails')->assertMissing($clip->posterImage);
    }

    /** @test */
    public function deleting_an_asset_should_update_clip_poster_image_column()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => $file = FileFactory::videoFile()]);

        $this->delete($clip->assets()->first()->path());

        $clip->refresh();

        $this->assertNull($clip->posterImage);
    }

    /** @test */
    public function it_should_queue_if_users_select_to_convert_to_hls()
    {
        Queue::fake();

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post(route('admin.assets.store', $clip), [
            'asset'                 => $file = FileFactory::videoFile(),
            'should_convert_to_hls' => true,
        ]);

        Queue::assertPushed(ConvertVideoForStreaming::class);
    }
}
