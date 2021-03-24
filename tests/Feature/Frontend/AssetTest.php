<?php

namespace Tests\Feature\Frontend;

use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_authenticated_user_can_upload_a_video_file()
    {
        $clip = ClipFactory::create();

        $this->post($clip->adminPath() . '/assets', ['asset' => $file  = FileFactory::videoFile()])
            ->assertRedirect('login');
    }
}
