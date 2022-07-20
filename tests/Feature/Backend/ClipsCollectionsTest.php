<?php

namespace Tests\Feature\Backend;

use App\Models\Clip;
use App\Models\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClipsCollectionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_toggle_clips_to_a_collection(): void
    {
        $this->signInRole('admin');

        $attributes = [
            'ids' => Clip::factory(2)->create()->pluck('id')->flatten()->all(),
        ];

        $this->post(route('collections.toggleClips', $collection = Collection::factory()->create()), $attributes)
            ->assertRedirect();

        $this->assertDatabaseHas('clip_collection', [
            'clip_id' => Clip::all()->first()->id,
            'collection_id' => $collection->id,
        ]);

        $this->post(route('collections.toggleClips', $collection), $attributes)
            ->assertRedirect();

        $this->assertDatabaseMissing('clip_collection', [
            'clip_id' => Clip::all()->first()->id,
            'collection_id' => $collection->id,
        ]);
    }
}
