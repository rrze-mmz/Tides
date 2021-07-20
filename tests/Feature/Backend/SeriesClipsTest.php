<?php

namespace Tests\Feature\Backend;

use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\SeriesFactory;
use Tests\TestCase;

class SeriesClipsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function a_non_authorize_user_cannot_view_add_clip_to_series_form(): void
    {

        $this->get(route('series.clip.create', SeriesFactory::create()))->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_add_clip_to_series_form_for_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signIn();

        $this->get(route('series.clip.create', $series))->assertStatus(403);
    }

    /** @test */
    public function a_series_owner_can_add_a_clip_to_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->get(route('series.clip.create', $series))->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_view_add_clip_to_series_form_for_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInAdmin();

        $this->get(route('series.clip.create', $series))->assertStatus(200);
    }

    /** @test */
    public function a_series_owner_can_view_add_clip_to_course_form(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->get(route('series.clip.create', $series))->assertStatus(200)
            ->assertSee('title')
            ->assertSee('description')
            ->assertSee('organization')
            ->assertSee('tags')
            ->assertSee('acls')
            ->assertSee('semester')
            ->assertSee('isPublic');
    }

    /** @test */
    public function a_series_owner_can_add_clip_to_series(): void
    {
        $series  = SeriesFactory::ownedBy($this->signIn())->create();

        $this->post(route('series.clip.store', $series), Clip::factory()->raw());

        $this->assertEquals(1, $series->clips()->count());
    }
}
