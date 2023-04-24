<?php

namespace Tests\Feature\Http\Controllers\Frontend;

use App\Enums\Acl;
use App\Enums\Role;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Series;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_paginate_all_series_on_index_page(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $this->get(route('frontend.series.index'))->assertSee($series->title);
    }

    /** @test */
    public function a_visitor_cannot_manage_series(): void
    {
        $this->post(route('series.store'), [])->assertRedirect('login');
    }

    /** @test */
    public function it_keeps_backwards_compatibility_for_series_route(): void
    {
        $series = Series::factory()->create();

        $this->get('/course/id/'.$series->id)->assertRedirectToRoute('frontend.series.show', $series);
    }

    /** @test */
    public function it_list_all_clips_with_media_assets_for_visitors(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(2)->create();

        $clipWithoutAsset = Clip::factory()->create(['series_id' => $series->id]);

        $this->get(route('frontend.series.show', $series))->assertDontSee($clipWithoutAsset->title);
    }

    /** @test */
    public function it_shows_series_page_even_without_clips_for_series_owner(): void
    {
        $user = $this->signIn();

        $series = SeriesFactory::ownedBy($user)->create();

        auth()->logout();

        $this->get(route('frontend.series.show', $series))->assertForbidden();

        $this->actingAs($user)->get(route('frontend.series.show', $series))->assertOk();
    }

    /** @test */
    public function it_shows_not_authorized_page_for_non_public_series_for_visitors(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->notPublic()->create();

        $this->get(route('frontend.series.show', $series))->assertForbidden();
    }

    /** @test */
    public function it_shows_not_authorized_page_for_non_public_series_for_now_series_owner(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->notPublic()->create();

        $this->signIn();

        $this->get(route('frontend.series.show', $series))->assertForbidden();
    }

    /** @test */
    public function a_series_owner_can_view_a_non_public_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->withClips(2)->withAssets(1)->notPublic()->create();

        $this->get(route('frontend.series.show', $series))->assertOk();
    }

    /** @test */
    public function an_admin_can_view_a_non_public_series(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->notPublic()->create();

        $this->signInRole(Role::ADMIN);

        $this->get(route('frontend.series.show', $series))->assertOk();
    }

    /** @test */
    public function it_lists_all_public_clips(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $firstClip = Clip::find(1);
        $secondClip = Clip::find(2);

        $firstClip->is_public = false;
        $firstClip->save();

        $this->get(route('frontend.series.show', $series))
            ->assertSee($secondClip->title)
            ->assertDontSee($firstClip->title);
    }

    /** @test */
    public function it_lists_all_clips_acls(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $series->clips->each(function ($clip) {
            $clip->addAcls(collect([Acl::PORTAL(), Acl::PASSWORD()]));
        });

        $this->get(route('frontend.series.show', $series))
            ->assertSee(Acl::PORTAL->lower().','.Acl::PASSWORD->lower());
    }

    /** @test */
    public function it_shows_for_each_clip_if_is_locked_or_not(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $series->clips->each(function ($clip) {
            $clip->addAcls(collect([Acl::PORTAL()]));
        });

        $this->get(route('frontend.series.show', $series))->assertSee('Lock clip');

        $this->signIn();
        $this->get(route('frontend.series.show', $series))->assertSee('Unlock clip');
    }

    /** @test */
    public function it_list_all_clips_with_semester_info(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $this->get(route('frontend.series.show', $series))->assertSee($series->clips->first()->semester->name);
    }

    /** @test */
    public function it_shows_series_semester_info(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $this->get(route('frontend.series.show', $series))
            ->assertSee($series->latestClip->semester->name);
    }

    /** @test */
    public function it_has_a_series_feed_button(): void
    {
        $this->get(route('frontend.series.show', SeriesFactory::withClips(2)->withAssets(1)->create()))
            ->assertSee('Feeds');
    }

    /** @test */
    public function it_shows_feed_links_for_different_formats(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(2)->create();

        $series->clips->first()->assets()->save(Asset::factory()->create(['width' => 640]));

        $this->get(route('frontend.series.show', $series))
            ->assertSee('QHD')
            ->assertSee('SD');
    }

    /** @test */
    public function it_shows_series_multiple_semester_info_if_has_clips_from_multiple_semester(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $firstClip = $series->clips()->first();

        $firstClip->semester_id = 3;

        $firstClip->save();

        $this->get(route('frontend.series.show', $series))
            ->assertSee($series->clips()->first()->semester->name.', '.$series->latestClip->semester->name);
    }

    /** @test */
    public function it_shows_a_subscribe_button_for_logged_in_users(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $this->get(route('frontend.series.show', $series))->assertDontSee('Subscribe');

        $this->signIn();

        $this->get(route('frontend.series.show', $series))->assertSee('Subscribe');
    }

    /** @test */
    public function it_has_an_unlock_button_if_series_has_a_passwort_protection(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $series->clips->each(function ($clip) {
            $clip->addAcls(collect([Acl::PASSWORD()]));
        });

        $this->get(route('frontend.series.show', $series))->assertSee('Unlock series');
    }

    /** @test */
    public function it_does_not_show_the_unlock_button_to_portal_admins(): void
    {
        $series = SeriesFactory::withClips(2)->withAssets(1)->create();

        $series->clips->each(function ($clip) {
            $clip->addAcls(collect([Acl::PASSWORD()]));
        });

        $this->signInRole(Role::ADMIN);

        $this->get(route('frontend.series.show', $series))->assertDontSee('Unlock series');
    }
}
