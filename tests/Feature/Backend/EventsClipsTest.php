<?php

namespace Tests\Feature\Backend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\SeriesFactory;
use Tests\TestCase;

class EventsClipsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_non_authorize_user_cannot_view_add_clip_to_series_form()
    {

        $this->get(route('seriesClips.create', SeriesFactory::create()))->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_add_clip_to_series_form_for_not_owned_series()
    {
        $series = SeriesFactory::create();

        $this->signIn();

        $this->get(route('seriesClips.create', $series))->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_view_add_clip_to_series_form_for_owned_series()
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->get(route('seriesClips.create', $series))->assertStatus(200);
    }


    /** @test */
    public function an_admin_user_can_view_add_clip_to_series_form_for_not_owned_series()
    {
        $series = SeriesFactory::create();

        $this->signInAdmin();

        $this->get(route('seriesClips.create', $series))->assertStatus(200);
    }
}
