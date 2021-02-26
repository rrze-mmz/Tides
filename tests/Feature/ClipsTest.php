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
    public function an_authenticated_user_can_create_a_clip()
    {
        $this->actingAs(User::factory()->create());

        $title = $this->faker->sentence;

        $attributes = [
            'title' => $title,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/clips',$attributes)->assertRedirect('/clips');

        $this->assertDatabaseHas('clips', $attributes);

        $this->get('/clips')->assertSee($attributes['title']);
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
    public function a_visitor_can_view_a_clip()
    {
        $this->actingAs(User::factory()->create());

        $clip = Clip::factory()->create();

        $this->get($clip->path())
            ->assertSee($clip->title)
            ->assertSee($clip->description);
    }

}
