<?php

namespace Tests\Feature;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetsTest extends TestCase {

    use WithFaker, RefreshDatabase;


    /** @test */
    public function an_authenticated_user_can_upload_a_video_file()
    {
        $this->actingAs(User::factory()->create());

        $clip = Clip::factory()->create();

        $uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            dirname(__DIR__, 3) . '/storage/tests/Big_Buck_Bunny.mp4',
            'Big_Buck_Bunny.mp4', 'video/mp4', null, true);

        $uploadedFile = UploadedFile::createFromBase($uploadedFile);

        $this->post($clip->adminPath() . '/assets', ['uploadedFile' => $uploadedFile])
            ->assertRedirect($clip->path());

        Storage::disk('public')->assertExists('/videos/' . $uploadedFile->hashName());

        Storage::disk('public')->delete('/videos/' . $uploadedFile->hashName());

        Storage::disk('public')->assertMissing('/videos/' . $uploadedFile->hashName());
    }
}
