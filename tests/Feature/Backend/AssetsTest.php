<?php

namespace Tests\Feature;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetsTest extends TestCase {

    use  RefreshDatabase;


    /** @test */
    public function an_authenticated_user_can_upload_a_video_file()
    {
        $this->signIn();

        $clip = Clip::factory()->create();

        $file = UploadedFile::fake()->create('video.mp4', '10000','video/mp4');

        $this->post($clip->adminPath() . '/assets', ['uploadedFile' => $file])
            ->assertRedirect($clip->adminPath());

        $this->assertDatabaseHas('assets', ['uploadedFile' => $clip->assets()->first()->uploadedFile]);

        Storage::disk('videos')->assertExists($file->hashName());

        Storage::disk('videos')->delete($file->hashName());

        Storage::disk('videos')->assertMissing($file->hashName());
    }
}
