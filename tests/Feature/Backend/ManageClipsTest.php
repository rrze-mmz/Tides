<?php

namespace Tests\Feature;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ClipFactory;
use Tests\TestCase;

class ManageClipsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_visitor_cannot_manage_clips()
    {
        $clip = ClipFactory::create();

        $this->post('/admin/clips', [])->assertRedirect('login');

        $this->get('/admin/clips/create')->assertRedirect('login');

        $this->patch($clip->adminPath(), [])->assertRedirect('login');

        $this->delete($clip->adminPath())->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_see_the_create_clip_form()
    {
        $this->signIn();

        $this->get('/admin/clips/create')->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_can_view_the_edit_clip_form()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->adminPath())->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_clip_edit_form_from_not_owned_clips()
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $this->get($clip->adminPath())->assertStatus(403);
    }

    /** @test */
    public function a_clip_requires_a_title()
    {
        $this->signIn();

        $attributes = Clip::factory()->raw(['title'=> '']);

        $this->post('/admin/clips', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_clip()
    {
        $this->signIn();

        $this->get('/admin/clips/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('/admin/clips',$attributes = Clip::factory()->raw())
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function an_authenticated_user_can_update_an_owned_clip()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->path())->assertSee($clip->title);

        $attributes = [
            'title'=>'changed',
            'description' => 'changed'
        ];

        $this->patch($clip->adminPath(), $attributes);

        $clip = $clip->refresh();

        $this->assertDatabaseHas('clips', $attributes);

        $this->get($clip->adminPath())->assertSee('changed');
    }

    /** @test */
    public function an_authenticated_user_cannot_update_a_not_owned_clip()
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $attributes = [
            'title'=>'changed',
            'description' => 'changed'
        ];

        $this->patch($clip->adminPath(), $attributes)->assertStatus(403);

        $this->assertDatabaseMissing('clips', $attributes);
    }

    /** @test */
    public function updating_a_clip_will_update_clip_slug()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->patch($clip->adminPath(), ['title'=>'Title changed']);

        $clip->refresh();

        $this->assertEquals('Title changed', $clip->title);

        $this->assertEquals('title-changed', $clip->slug);
    }

    /** @test */
    public function an_authenticated_user_cannot_delete_a_not_owned_clip()
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $this->delete($clip->adminPath())->assertStatus(403);

        $this->assertDatabaseHas('clips', $clip->only('id'));
    }

    /** @test */
    public function an_authenticated_user_can_delete_owned_clip()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->delete($clip->adminPath())->assertRedirect(route('clips.index'));

        $this->assertDatabaseMissing('clips', $clip->only('id'));
    }
}
