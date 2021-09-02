<?php


namespace Tests\Feature\Frontend;

use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_visitor_cannot_upload_a_video_file(): void
    {
        $clip = ClipFactory::create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()])
            ->assertRedirect('login');
    }

    /** @test */
    public function a_visitor_cannot_view_dropzone_files_for_a_clip(): void
    {
        $clip = ClipFactory::create();

        $this->get(route('admin.clips.dropzone.listFiles', $clip))->assertRedirect('login');
    }
}
