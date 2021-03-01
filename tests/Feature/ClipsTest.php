<?php

namespace Tests\Feature;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClipsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_visitor_cannot_create_a_clip()
    {
        $attributes = Clip::factory()->raw();

        $this->post('/clips', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function a_vistor_cannot_update_a_clip()
    {
        $clip = Clip::factory()->create();

        $this->patch($clip->path(), ['title'=>'changed'])
            ->assertRedirect('login');
    }

    /** @test */
    public function a_visitor_can_view_a_clip()
    {
        $clip = Clip::factory()->create();

        $this->get($clip->path())
            ->assertSee($clip->title)
            ->assertSee($clip->description);
    }

    /** @test */
    public function a_clip_requires_a_title()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Clip::factory()->raw(['title'=> '']);

//        dd($attributes);
        $this->post('/clips', $attributes)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_clip()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/clips',$attributes)->assertRedirect('/clips');

        $this->assertDatabaseHas('clips', $attributes);

        $this->get('/clips')->assertSee($attributes['title']);
    }

    /** @test */
    public function an_authenticated_user_can_update_a_clip()
    {
        $this->actingAs(User::factory()->create());

        $clip = Clip::factory()->create();

        $this->get($clip->path())->assertSee($clip->title);

        $attributes = [
            'title'=>'changed',
            'description' => 'changed'
        ];
        $this->patch($clip->path(), $attributes);

        $clip = $clip->refresh();

        $this->assertDatabaseHas('clips', $attributes);

        $this->get($clip->path())->assertSee('changed');
    }

    /** @test */
    public function updating_a_clip_will_update_clip_slug()
    {
        $this->actingAs(User::factory()->create());

        $clip = Clip::factory()->create();

        $this->patch($clip->path(), ['title'=>'Title changed']);

        $clip = $clip->refresh();

        $this->assertEquals('Title changed', $clip->title);

        $this->assertEquals('title-changed', $clip->slug);
    }
}
