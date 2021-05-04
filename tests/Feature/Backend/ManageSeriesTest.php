<?php

namespace Tests\Feature\Backend;

use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\SeriesFactory;
use Tests\TestCase;

class ManageSeriesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_authenticated_user_can_see_the_create_series_form_and_all_form_fields(): void
    {
        $this->signIn();

        $this->get(route('series.create'))->assertSee('title')
            ->assertSee('description');

        $this->get(route('series.create'))->assertStatus(200)
            ->assertViewIs('backend.series.create');
    }

    /** @test */
    public function it_requires_a_title_when_creating_a_new_series(): void
    {
        $this->signIn();

        $attributes = Series::factory()->raw(['title'=> '']);

        $this->post(route('series.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_series(): void
    {
        $this->signIn();

        $this->get('/admin/series/create')->assertStatus(200);

        $this->followingRedirects()
            ->post(route('series.store'), $attributes = Series::factory()->raw())
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function a_series_owner_can_view_edit_form_fields(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->get($series->adminPath())->assertStatus(200);

        $this->get($series->adminPath())->assertSee('title')
            ->assertSee('description');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_edit_clip_form_for_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signIn();

        $this->get($series->adminPath())->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_edit_an_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInAdmin();

        $this->get($series->adminPath())->assertStatus(200);
    }

    /** @test */
    public function it_has_an_add_clips_button(): void
    {
        $this->get(route('series.edit', SeriesFactory::ownedBy($this->signIn())->create()))->assertSee('Add new clip');
    }

    /** @test */
    public function it_has_go_to_public_page_button(): void
    {
        $this->get(route('series.edit', SeriesFactory::ownedBy($this->signIn())->create()))->assertSee('Go to public page');
    }

    /** @test */
    public function edit_series_page_should_display_belonging_clips(): void
    {
        $series =  SeriesFactory::ownedBy($this->signIn())->withClips(2)->create();

        $this->get(route('series.edit',$series))->assertSee($series->clips()->first()->title);
    }

    /** @test */
    public function it_requires_a_title_creating_a_series(): void
    {
        $this->signIn();

        $attributes = Series::factory()->raw(['title'=> '']);

        $this->post(route('series.store'),$attributes)->assertSessionHasErrors('title');}

    /** @test */
    public function create_series_form_should_remember_old_values_on_validation_error(): void
    {
        $this->signIn();

        $attributes = [
            'title' => 'Series title',
            'description' => 'te'
        ];

        $this->post(route('series.store'), $attributes);

        $this->followingRedirects();

        $this->get(route('series.create'))->assertSee($attributes);
    }

    /** @test */
    public function a_series_owner_can_update_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->get($series->path())->assertSee($series->title);

        $this->patch($series->adminPath(),[
            'title' => 'changed',
            'description'   => 'changed'
        ]);

        $series = $series->refresh();

        $this->assertDatabaseHas('series', [
            'title' => 'changed',
            'description' => 'changed',
        ]);

        $this->get($series->adminPath())->assertSee('changed');
    }

    /** @test */
    public function an_authenticated_user_cannot_update_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signIn();

        $this->patch($series->adminPath(),[
            'title' => 'changed',
            'description'   => 'changed'
        ])->assertStatus(403);

        $this->assertDatabaseMissing('series', ['title'=>'changed']);
    }

    /** @test */
    public function an_admin_user_can_update_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInAdmin();

        $this->followingRedirects()->patch($series->adminPath(),[
            'title'       => 'changed',
            'description' => 'changed'
        ])->assertStatus(200);

        $this->assertDatabaseHas('series', ['title'=>'changed']);
    }

    /** @test */
    public function an_authenticated_user_cannot_delete_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signIn();

        $this->delete($series->adminPath())->assertStatus(403);

        $this->assertDatabaseHas('series', $series->only('id'));
    }

    /** @test */
    public function an_admin_user_can_delete_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInAdmin();

        $this->followingRedirects()->delete($series->adminPath())->assertStatus(200);

        $this->assertDatabaseMissing('series', $series->only('id'));
    }

    /** @test */
    public function a_series_owner_can_delete_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->followingRedirects()->delete($series->adminPath())->assertStatus(200);

        $this->assertDatabaseMissing('series', $series->only('id'));
    }
}
