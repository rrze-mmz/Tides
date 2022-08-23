<?php

namespace Tests\Feature\Console\Commands;

use App\Enums\Content;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InsertSmilAssetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_message_if_smil_is_created(): void
    {
        $clip = ClipFactory::withAssets(4)->create();

        $this->artisan('smil:insert')->expectsOutput('Finish clip ID '.$clip->id);
    }

    /** @test */
    public function it_generates_a_smil_file_and_inserts_it_to_database(): void
    {
        Storage::fake('videos');

        $clip = ClipFactory::withAssets(4)->create();

        $this->assertEquals(4, $clip->assets()->count());

        $this->artisan('smil:insert');

        $smil = $clip->getAssetsByType(Content::SMIL)->first();

        $this->assertDatabaseHas('assets', ['id' => $smil->id]);

        Storage::disk('videos')->assertExists($smil->path.$smil->original_file_name);
    }
}
