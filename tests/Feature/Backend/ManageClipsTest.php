<?php


namespace Tests\Feature\Backend;

use App\Models\Clip;
use App\Models\Tag;
use Facades\Tests\Setup\ClipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageClipsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_see_the_create_clip_form_and_all_form_fields()
    {
        $this->signIn();

        $this->get(route('clips.create'))->assertSee('title')
            ->assertSee('description')
            ->assertSee('tags');

        $this->get(route('clips.create'))->assertStatus(200)
            ->assertViewIs('backend.clips.create');
    }

    /** @test */
    public function an_authenticated_user_can_view_the_edit_clip_form_add_all_form_fields()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->adminPath())->assertStatus(200);

        $this->get($clip->adminPath())->assertSee('title')
            ->assertSee('description')
            ->assertSee('tags');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_clip_edit_form_for_not_owned_clips()
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $this->get($clip->adminPath())->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_edit_a_not_owned_clip()
    {
        $clip = ClipFactory::create();

        $this->signInAdmin();

        $this->get($clip->adminPath())->assertStatus(200);
    }


    /** @test */
    public function it_requires_a_title_creating_a_new_clip()
    {
        $this->signIn();

        $attributes = Clip::factory()->raw(['title' => '']);

        $this->post(route('clips.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_clip()
    {
        $this->signIn();

        $this->get('/admin/clips/create')->assertStatus(200);

        $this->followingRedirects()
            ->post(route('clips.store'), $attributes = Clip::factory()->raw())
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_clip_with_tags()
    {
        $this->signIn();

        $attributes = Clip::factory()->raw([
            'tags' => ['php', 'example', 'oop']
        ]);

        $this->followingRedirects()->post(route('clips.store'), $attributes)->assertSee($attributes['tags']);

        $clip = Clip::first();

        $this->assertDatabaseCount('tags', 3);

        $this->assertEquals(3, $clip->tags()->count());
    }

    /** @test */
    public function an_authenticated_user_can_remove_clip_tags()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $clip->tags()->sync(Tag::factory()->create());

        $this->patch($clip->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed',
            'tags'        => []
        ]);

        $this->assertEquals(0, $clip->tags()->count());
    }

    /** @test */
    public function an_authenticated_user_can_update_clip_tags()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $tag = Tag::factory()->create();

        $clip->tags()->sync(Tag::factory()->create());

        $this->patch($clip->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed',
            'tags'        => [$tag->name, 'another tag']
        ]);

        $this->assertEquals(2, $clip->tags()->count());
    }

    /** @test */
    public function create_clip_form_should_remember_old_values_on_validation_error()
    {
        $this->signIn();

        $attributes = [
            'title'       => 'Clip title',
            'description' => $this->faker->sentence(500),
        ];

        $this->post(route('clips.store'), $attributes);

        $this->followingRedirects();

        $this->get(route('clips.create'))->assertSee($attributes);

    }

    /** @test */
    public function an_authenticated_user_can_update_his_clip()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->path())->assertSee($clip->title);

        $this->patch($clip->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed'
        ]);

        $clip = $clip->refresh();

        $this->assertDatabaseHas('clips', [
            'title'       => 'changed',
            'description' => 'changed'
        ]);

        $this->get($clip->adminPath())->assertSee('changed');
    }

    /** @test */
    public function an_authenticated_user_cannot_update_a_not_owned_clip()
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $attributes = [
            'title'       => 'changed',
            'description' => 'changed'
        ];

        $this->patch($clip->adminPath(), $attributes)->assertStatus(403);

        $this->assertDatabaseMissing('clips', $attributes);
    }

    /** @test */
    public function an_admin_user_can_update_a_not_owned_clip()
    {
        $clip = ClipFactory::create();

        $this->signInAdmin();

        $attributes = [
            'title'       => 'changed',
            'description' => 'changed'
        ];

        $this->followingRedirects()->patch($clip->adminPath(), $attributes)->assertStatus(200);

        $this->assertDatabaseHas('clips', $attributes);
    }

    /** @test */
    public function updating_a_clip_will_update_clip_slug()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->patch($clip->adminPath(), ['title' => 'Title changed']);

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
    public function an_admin_user_can_delete_a_not_owned_clip()
    {
        $clip = ClipFactory::create();

        $this->signInAdmin();

        $this->followingRedirects()->delete($clip->adminPath())->assertStatus(200);

        $this->assertDatabaseMissing('clips', $clip->only('id'));
    }


    /** @test */
    public function an_authenticated_user_can_delete_owned_clip()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->delete($clip->adminPath())->assertRedirect(route('clips.index'));

        $this->assertDatabaseMissing('clips', $clip->only('id'));
    }
}
