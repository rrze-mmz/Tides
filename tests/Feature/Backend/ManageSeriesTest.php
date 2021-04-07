<?php

namespace Tests\Feature\Backend;

use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageSeriesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_authenticated_user_can_see_the_create_series_form_and_all_form_fields()
    {
        $this->signIn();

        $this->get(route('series.create'))->assertSee('title')
            ->assertSee('description');

        $this->get(route('series.create'))->assertStatus(200)
            ->assertViewIs('backend.series.create');
    }

    /** @test */
    public function it_requires_a_title_when_creating_a_new_series()
    {
        $this->signIn();

        $attributes = Series::factory()->raw(['title'=> '']);

        $this->post(route('series.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_series()
    {
        $this->signIn();

        $this->get('/admin/series/create')->assertStatus(200);

        $this->followingRedirects()
            ->post(route('series.store'), $attributes = Series::factory()->raw())
            ->assertSee($attributes['title']);
    }

}
