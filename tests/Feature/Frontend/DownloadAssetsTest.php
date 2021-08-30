<?php

namespace Tests\Feature\Frontend;

use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;

class DownloadAssetsTest extends TestCase
{
    use RefreshDatabase;
    use withFaker;

    /*
     * this might be a Unit Test!!
     */
    /** @test */
    public function it_downloads_an_asset(): void
    {
        Storage::fake('videos');

        $clip = ClipFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('admin.assets.store', $clip), ['asset' => FileFactory::videoFile()]);

        $asset = $clip->assets()->first();

        $this->assertInstanceOf(BinaryFileResponse::class, response()->download(Storage::disk('videos')->path($asset->path)));

        Storage::disk('videos')->delete($asset->path);
    }
}
