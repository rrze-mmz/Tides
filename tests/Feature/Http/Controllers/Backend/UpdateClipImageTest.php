<?php

namespace Tests\Feature\Http\Controllers\Backend;

use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateClipImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_clip_image(): void
    {
        $this->signInRole('moderator');
        $clip = Clip::factory()->create();

        $this->put(route('update.clip.image', $clip), ['imageID' => 1])
            ->assertRedirectToRoute('clips.edit', $clip);

        $clip->refresh();
        $this->assertEquals(1, $clip->image_id);
    }
}
