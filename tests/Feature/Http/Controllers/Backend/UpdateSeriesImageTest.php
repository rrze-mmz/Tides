<?php

namespace Tests\Feature\Http\Controllers\Backend;

use App\Models\Series;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateSeriesImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_image_id_must_selected_to_update_series_image(): void
    {
        $this->signInRole('moderator');

        $series = Series::factory()->create();

        $this->put(route('update.series.image', $series), ['imageID' => ''])
            ->assertSessionHasErrors('imageID');
    }

    /** @test */
    public function it_can_update_images_for_series_and_for_all_series_clips(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))
            ->withClips(3)
            ->create();

        $this->assertEquals(2, $series->image_id);
        $this->assertEquals(2, $series->clips()->first()->image_id);

        $this->put(route('update.series.image', $series), ['imageID' => 1, 'assignClips' => 'on']);

        $series->refresh();

        $this->assertEquals(1, $series->image_id);
        $this->assertEquals(1, $series->clips()->first()->image_id);
    }

    /** @test */
    public function it_can_update_series_image(): void
    {
        $this->signInRole('moderator');

        $series = Series::factory()->create();

        $this->put(route('update.series.image', $series), ['imageID' => 1])
            ->assertRedirectToRoute('series.edit', $series);

        $series->refresh();
        $this->assertEquals(1, $series->image_id);
    }
}
