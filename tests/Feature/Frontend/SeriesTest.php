<?php

namespace Tests\Feature\Frontend;

use App\Models\Acl;
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

        $this->get(route('frontend.series.show', $series))->assertDontSee($clipWithoutAsset->title);
    }

    /** @test */
    public function it_shows_not_authorized_page_for_non_public_series_for_visitors(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $series->isPublic = false;

        $series->save();

        $this->get(route('frontend.series.show', $series))->assertStatus(403);
    }

    /** @test */
    public function it_shows_not_authorized_page_for_non_public_series_for_now_series_owner(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $series->isPublic = false;

        $series->save();

        $this->signIn();

        $this->get(route('frontend.series.show', $series))->assertStatus(403);
    }

    /** @test */
    public function a_series_owner_can_view_a_non_public_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->withClips(2)->withAssets(1)->create();

        $series->isPublic = false;

        $series->save();

        $this->get(route('frontend.series.show', $series))->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_view_a_non_public_series(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $series->isPublic = false;

        $series->save();

        $this->signInAdmin();

        $this->get(route('frontend.series.show', $series))->assertStatus(200);
    }

    /** @test */
    public function it_lists_all_clips_acls(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $firstClip = Clip::find(1);
        $secondClip = Clip::find(2);

        $firstClip->addAcls(collect(['1']));
        $secondClip->addAcls(collect(['1','2']));

        $this->get(route('frontend.series.show',$series))->assertSee(Acl::find(1)->name)->assertSee(Acl::find(2)->name);
    }
}
