<?php

namespace Tests\Feature;

use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Facades\Tests\Setup\ClipFactory;
use Tests\TestCase;

class AssetsTest extends TestCase {

    use  RefreshDatabase;


    /** @test */
    public function an_authenticated_user_can_upload_a_video_file()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $file = UploadedFile::fake()->create('video.mp4', '10000','video/mp4');

        $this->post($clip->adminPath() . '/assets', ['uploadedFile' => $file])
            ->assertRedirect($clip->adminPath());

        $this->assertDatabaseHas('assets', ['uploadedFile' => $clip->assets()->first()->uploadedFile]);

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

        $file = UploadedFile::fake()->create('video.mp4', '10000','video/mp4');

        $this->post($clip->adminPath() . '/assets', ['uploadedFile' => $file]);

        $this->assertDatabaseHas('assets', ['uploadedFile' => $clip->assets()->first()->uploadedFile]);

        $this->delete($clip->assets->first()->path());

        $this->assertDeleted('assets', ['uploadedFile' => $file]);

        Storage::disk('videos')->assertMissing($file->hashName());
    }
}
