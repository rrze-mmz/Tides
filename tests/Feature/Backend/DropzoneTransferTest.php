<?php


namespace Tests\Feature\Backend;

use App\Jobs\ConvertVideoForStreaming;
use App\Jobs\SendEmail;
use App\Jobs\TransferDropzoneFiles;
use App\Mail\VideoUploaded;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
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
        $this->get(route('admin.clips.dropzone.listFiles', ClipFactory::ownedBy($this->signIn())->create()))
            ->assertStatus(200)
            ->assertViewIs('backend.clips.dropzone.listFiles');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_drop_zone_files_for_a_not_owned_clip()
    {
        $this->signIn();

        $this->get(route('admin.clips.dropzone.listFiles', ClipFactory::create()))->assertStatus(403);
    }

    /** @test */
    public function dropzone_transfer_view_should_list_all_files_with_sha1_hash()
    {
        $disk = Storage::fake('video_dropzone');

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get(route('admin.clips.dropzone.listFiles', ['clip' => $clip]))
            ->assertStatus(200)
            ->assertSee('no videos');

        $disk->putFileAs('', File::create('export_video.mp4', 1000), 'export_video.mp4');

        $this->get(route('admin.clips.dropzone.listFiles', ['clip' => $clip]))
            ->assertSee(sha1('export_video.mp4'));
    }

    /** @test */
    public function an_authenticated_user_cannot_transfer_drop_zone_files_for_a_not_owned_clip()
    {
        $this->signIn();

        $this->post(route('admin.clips.dropzone.transfer', ClipFactory::create()))->assertStatus(403);
    }

    /** @test */
    public function it_transfers_files_from_dropzone_to_clip()
    {
        Storage::fake('videos');

        Storage::fake('video_dropzone')->putFileAs('', FileFactory::videoFile(), 'export_video_1080.mp4');
        Storage::fake('video_dropzone')->putFileAs('', FileFactory::videoFile(), 'export_video_720.mp4');
        Storage::fake('video_dropzone')->putFileAs('', FileFactory::videoFile(), 'export_video_360.mp4');


        $files = fetchDropZoneFiles()->sortBy('date_modified');

        $videoHD =$files->first();
        $videoSD =$files->last();

        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->followingRedirects()->post(route('admin.clips.dropzone.transfer',$clip),
            ["files" => [$videoHD['hash'], $videoSD['hash']]]
        )->assertStatus(200);


        $this->get($clip->adminPath())
            ->assertSee($videoHD['name'])
            ->assertSee($videoSD['name']);
    }

    /** @test */
    public function it_should_queue_the_transfer_dropzone_to_clip_job()
    {
        Queue::fake();

        $this->post(route('admin.clips.dropzone.transfer',ClipFactory::ownedBy($this->signIn())->create()), [
            'files'=>[sha1('test_file_name')]]);

        Queue::assertPushed(TransferDropzoneFiles::class);

    }

    /** @test */
    public function it_should_send_an_email_after_transfer_job_is_completed()
    {
        Mail::fake();

        $this->post(route('admin.clips.dropzone.transfer',ClipFactory::ownedBy($this->signIn())->create()), [
            'files'=>[sha1('test_file_name')]]);

        Mail::assertQueued(VideoUploaded::class);
    }
}
