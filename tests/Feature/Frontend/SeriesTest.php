<?php

namespace Tests\Feature\Frontend;

use App\Models\Clip;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_visitor_cannot_manage_series(): void
    {
        $this->post(route('series.store'),[])->assertRedirect('login');
    }

    /** @test */
    public function it_list_all_clips_with_media_assets_for_visitors(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $clipWithoutAsset = Clip::factory()->create(['series_id'=>$series->id]);

        $this->get(route('series.show', $series))->assertDontSee($clipWithoutAsset->title);
    }
}
