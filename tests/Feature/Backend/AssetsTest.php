<?php

namespace Tests\Feature\Backend;

use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Tests\TestCase;

class AssetsTest extends TestCase {

    use  RefreshDatabase, WithFaker;

    /** @test */
    public function an_authenticated_user_can_upload_a_video_file()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post($clip->adminPath() . '/assets', ['asset' => $file  = FileFactory::videoFile()])
            ->assertRedirect($clip->adminPath());

        $this->assertDatabaseHas('assets', ['path' => $clip->assets()->first()->path]);

        Storage::disk('videos')->assertExists($file->hashName());

        Storage::disk('videos')->delete($file->hashName());

        Storage::disk('videos')->assertMissing($file->hashName());
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

        $this->post($clip->adminPath() . '/assets', ['asset' => $file  = FileFactory::videoFile()]);

        $this->assertDatabaseHas('assets', ['path' => $clip->assets()->first()->path]);

        Storage::disk('videos')->delete($file->hashName());

        $this->assertDeleted('assets', ['asset' => $file]);

        Storage::disk('videos')->assertMissing($file->hashName());
    }

    /** @test */
    public function uploading_an_asset_should_save_asset_duration()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->post($clip->adminPath() . '/assets', ['asset' => $file  = FileFactory::videoFile()]);

        $this->assertEquals(10, FFMpeg::open($clip->assets()->first()->path)->getDurationInSeconds());

        $clip->assets()->first()->delete();
    }
}
