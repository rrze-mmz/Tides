<?php


namespace Tests\Feature\Backend;

use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class DropzoneTransferTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function an_authenticated_user_can_see_copy_assets_from_dropzone_button()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->adminPath())->assertSee('Transfer files from drop zone');
    }

    /** @test */
    public function it_has_a_transfer_view_for_dropzone_files()
    {
        $this->get(route('admin.clips.dropzone.listFiles',  ClipFactory::ownedBy($this->signIn())->create()))
            ->assertStatus(200)
            ->assertViewIs('backend.clips.transfer');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_drop_zone_files_for_a_not_owned_clip()
    {
        $this->signIn();

        $this->get(route('admin.clips.dropzone.listFiles',ClipFactory::create()))->assertStatus(403);
    }

    /** @test */
    public function dropzone_transfer_view_should_list_all_files()
    {
        $disk = Storage::fake('video_dropzone');

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get(route('admin.clips.dropzone.listFiles',['clip'=>$clip]))
            ->assertStatus(200)
            ->assertSee('no videos');

        $disk->putFileAs('',File::create('export_video.mp4',1000),'export_video.mp4');

        $this->get(route('admin.clips.dropzone.listFiles',['clip'=>$clip]))
            ->assertSee('export_video.mp4');
    }
}
