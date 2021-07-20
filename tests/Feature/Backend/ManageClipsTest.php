<?php


namespace Tests\Feature\Backend;

use App\Models\Clip;
use App\Models\Tag;
use App\Services\OpencastService;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class ManageClipsTest extends TestCase {
    use RefreshDatabase;
    use WithFaker;
    use WorksWithOpencastClient;

    private OpencastService $opencastService;
    private string $flashMessageName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->flashMessageName = 'flashMessage';
    }

    /** @test */
    public function it_paginates_users_clips_in_dashboard_index_page(): void
    {
        Clip::factory(20)->create(['owner_id' => $this->signIn()]);

        $this->get(route('clips.index') . '?page=2')->assertDontSee('You have no series yet');
    }

    /** @test */
    public function it_paginates_all_clips_in_dashboard_index_page_for_admin_user(): void
    {
        Clip::factory(20)->create();

        $this->signInAdmin();

        $this->get(route('clips.index') . '?page=2')->assertDontSee('You have no series yet');
    }

    /** @test */
    public function it_requires_a_title_creating_a_new_clip(): void
    {
        $this->signIn();

        $attributes = Clip::factory()->raw(['title' => '']);

        $this->post(route('clips.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function it_requires_a_semester_creating_a_new_clip(): void
    {
        $this->signIn();

        $attributes = Clip::factory()->raw(['semester_id' => '']);

        $this->post(route('clips.store'), $attributes)->assertSessionHasErrors('semester_id');
    }

    /** @test */
    public function it_must_have_a_strong_password_if_any(): void
    {
        $this->signIn();

        $this->post(route('clips.store', Clip::factory()->raw([
            'title'    => 'This is a test',
            'password' => '1234',
        ])
        ))->assertSessionHasErrors('password');

        $this->followingRedirects()->post(route('clips.store', Clip::factory()->raw([
            'title'    => 'This is a test',
            'password' => '1234qwER',
        ])
        ))->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_clip(): void
    {
        $this->signIn();

        $this->followingRedirects()
            ->post(route('clips.store'), $attributes = Clip::factory()->raw())
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function an_authenticated_user_can_see_the_create_clip_form_and_all_form_fields(): void
    {
        $this->signIn();

        $this->get(route('clips.create'))
            ->assertSee('title')
            ->assertSee('description')
            ->assertSee('organization')
            ->assertSee('tags')
            ->assertSee('acls')
            ->assertSee('semester')
            ->assertSee('isPublic');

        $this->get(route('clips.create'))->assertStatus(200)
            ->assertViewIs('backend.clips.create');
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_clip_is_created(): void
    {
        $this->signIn();

        $this->post(route('clips.store'), Clip::factory()->raw())->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function an_authenticated_user_can_view_the_edit_clip_form_add_all_form_fields(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->adminPath())->assertStatus(200);

        $this->get($clip->adminPath())->assertSee('title')
            ->assertSee('description')
            ->assertSee('tags')
            ->assertSee('organization')
            ->assertSee('tags')
            ->assertSee('semester')
            ->assertSee('isPublic')
            ->assertSee('acls');
    }

    /** @test */
    public function it_lists_whether_a_clip_belongs_to_a_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->withClips(2)->create();

        $this->get(route('clips.edit', $series->clips()->first()))->assertSee($series->title);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_clip_edit_form_for_not_owned_clips(): void
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $this->get($clip->adminPath())->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_edit_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInAdmin();

        $this->get($clip->adminPath())->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_clip_with_tags(): void
    {
        $this->withoutExceptionHandling();
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
    public function an_authenticated_user_can_create_a_clip_with_acls(): void
    {
        $this->signIn();

        $attributes = Clip::factory()->raw([
            'acls' => ['1', '2']
        ]);

        $this->post(route('clips.store'), $attributes);

        $clip = Clip::first();

        $this->assertEquals(2, $clip->acls()->count());
    }

    /** @test */
    public function an_authenticated_user_can_remove_clip_tags(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $clip->tags()->sync(Tag::factory()->create());

        $this->patch($clip->adminPath(), [
            'episode'         => '1',
            'title'           => 'changed',
            'description'     => 'changed',
            'organization_id' => '1',
            'tags'            => [],
            'semester_id'     => '1',
        ]);

        $this->assertEquals(0, $clip->tags()->count());
    }

    /** @test */
    public function an_authenticated_user_can_update_clip_tags(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $tag = Tag::factory()->create();

        $clip->tags()->sync(Tag::factory()->create());

        $this->patch($clip->adminPath(), [
            'episode'         => '1',
            'title'           => 'changed',
            'description'     => 'changed',
            'organization_id' => '1',
            'tags'            => [$tag->name, 'another tag'],
            'semester_id'     => '1',
        ]);

        $this->assertEquals(2, $clip->tags()->count());
    }

    /** @test */
    public function create_clip_form_should_remember_old_values_on_validation_error(): void
    {
        $this->signIn();

        $attributes = [
            'title'           => 'Clip title',
            'description'     => $this->faker->sentence(500),
            'organization_id' => '1',
            'semester_id'     => '1',
        ];

        $this->post(route('clips.store'), $attributes);

        $this->followingRedirects();

        $this->get(route('clips.create'))->assertSee($attributes);

    }

    /** @test */
    public function an_authenticated_user_can_update_his_clip(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->path())->assertSee($clip->title);

        $this->patch($clip->adminPath(), [
            'episode'         => '1',
            'title'           => 'changed',
            'description'     => 'changed',
            'organization_id' => '1',
            'semester_id'     => '1',
        ]);

        $clip = $clip->refresh();

        $this->assertDatabaseHas('clips', [
            'episode'         => '1',
            'title'           => 'changed',
            'description'     => 'changed',
            'organization_id' => '1',
            'semester_id'     => '1',
        ]);

        $this->get($clip->adminPath())->assertSee('changed');
    }

    /** @test */
    public function an_authenticated_user_cannot_update_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $attributes = [
            'episode'         => '1',
            'title'           => 'changed',
            'description'     => 'changed',
            'organization_id' => '1',
            'semester_id'     => '1',
        ];

        $this->patch($clip->adminPath(), $attributes)->assertStatus(403);

        $this->assertDatabaseMissing('clips', $attributes);
    }

    /** @test */
    public function an_admin_user_can_update_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInAdmin();

        $attributes = [
            'episode'         => '1',
            'title'           => 'changed',
            'description'     => 'changed',
            'organization_id' => '1',
            'semester_id'     => '1',
        ];

        $this->followingRedirects()->patch($clip->adminPath(), $attributes)->assertStatus(200);

        $this->assertDatabaseHas('clips', $attributes);
    }

    /** @test */
    public function it_updates_clip_slug_if_title_is_changed(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->patch($clip->adminPath(), [
            'episode'         => '1',
            'title'           => 'Title changed',
            'organization_id' => '1',
            'semester_id'     => '1']);

        $clip->refresh();

        $this->assertEquals('title-changed', $clip->slug);
    }

    /** @test */
    public function it_does_not_update_clip_slug_if_title_is_not_changed()
    {
        $this->signIn();

        $this->post(route('clips.store'), [
            'episode'         => '1',
            'title'           => 'Test clip',
            'description'     => 'test',
            'organization_id' => '1',
            'semester_id'     => '1'
        ]);

        $clip = Clip::find(1);

        $this->patch($clip->adminPath(), ['episode' => '2', 'title' => 'Test clip', 'description' => 'test']);

        $clip->refresh();

        $this->assertEquals('test-clip', $clip->slug);
    }

    /** @test */
    public function it_has_an_upload_button_in_edit_form(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get(route('clips.edit', $clip))->assertSee('Upload');
    }

    /** @test */
    public function it_has_a_convert_to_hls_option_in_edit_form(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get(route('clips.edit', $clip))->assertSee('Convert to HLS?');
    }

    /** @test */
    public function it_shows_an_lms_test_link_if_clip_has_an_lms_acl_and_user_is_admin(): void
    {
        $this->withoutExceptionHandling();
        $userClip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get(route('clips.edit', $userClip))->assertDontSee('LMS Test Link');

        $adminClip = ClipFactory::ownedBy($this->signInAdmin())->create();

        $this->get(route('clips.edit', $adminClip))->assertDontSee('LMS Test Link');

        $adminClip->addAcls(collect(['2']));

        $this->get(route('clips.edit', $adminClip))->assertSee('LMS Test Link');
    }

    /** @test */
    public function it_has_an_ingest_to_opencast_button_if_opencast_server_exists(): void
    {
        $mockHandler = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);

        $mockHandler->append($this->mockHealthResponse());

        $this->get(route('clips.edit', ClipFactory::ownedBy($this->signIn())->create()))
            ->assertSee('Ingest to Opencast');
    }

    /** @test */
    public function it_hides_opencast_button_if_opencast_server_does_not_exists(): void
    {
        $mockHandler = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);

        $mockHandler->append(new Response());

        $this->get(route('clips.edit', ClipFactory::ownedBy($this->signIn())->create()))
            ->assertDontSee('Ingest to Opencast');
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_clip_is_updated()
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->patch($clip->adminPath(), [
            'title'           => 'changed',
            'description'     => 'changed',
            'organization_id' => '1',
        ])->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function an_authenticated_user_cannot_delete_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signIn();

        $this->delete($clip->adminPath())->assertStatus(403);

        $this->assertDatabaseHas('clips', $clip->only('id'));
    }

    /** @test */
    public function an_admin_user_can_delete_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInAdmin();

        $this->followingRedirects()->delete($clip->adminPath())->assertStatus(200);

        $this->assertDatabaseMissing('clips', $clip->only('id'));
    }

    /** @test */
    public function an_authenticated_user_can_delete_owned_clip(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->delete($clip->adminPath())->assertRedirect(route('clips.index'));

        $this->assertDatabaseMissing('clips', $clip->only('id'));
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_clip_is_deleted(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->delete($clip->adminPath())->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function it_can_toggle_comments(): void
    {
        $clip = ClipFactory::ownedBy($this->signIn())->create();

        $this->get($clip->path())->assertDontSee('Comments');

        $this->patch(route('clips.update', $clip), [
            'title'           => $clip->title,
            'episode'         => $clip->episode,
            'organization_id' => '1',
            'semester_id'     => '1',
            'allow_comments'  => 'on'
        ]);

        $this->get($clip->path())->assertSee('Comments');
    }

    /** @test */
    public function it_displays_previous_next_clip_id_links(): void
    {
        $this->signInAdmin();

        SeriesFactory::withClips(3)->create();

        $clip = Clip::find(2);
        $previousClip = Clip::find(1);
        $nextClip = Clip::find(3);

        $this->get($clip->adminPath())
            ->assertSee('Previous')
            ->assertSee('Next')
            ->assertSee($previousClip->adminPath())
            ->assertSee($nextClip->adminPath());
    }

    /** @test */
    public function it_hides_previous_clip_id_links_if_clip_is_the_first_on_a_series(): void
    {
        $this->signInAdmin();

        SeriesFactory::withClips(3)->create();

        $clip = Clip::find(1);
        $nextClip = Clip::find(2);

        $this->get($clip->adminPath())
            ->assertDontSee('Previous')
            ->assertSee('Next')
            ->assertSee($nextClip->adminPath());
    }

    /** @test */
    public function it_hides_next_clip_id_links_if_clip_is_the_last_on_a_series(): void
    {
        $this->signInAdmin();

        SeriesFactory::withClips(4)->create();

        $clip = Clip::find(4);

        $previousClip = Clip::find(3);


        $this->get($clip->adminPath())
            ->assertSee('Previous')
            ->assertDontSee('Next')
            ->assertSee($previousClip->adminPath());
    }

    /** @test */
    public function it_has_a_trigger_smil_file_button_if_clip_has_assets(): void
    {
        $clip = ClipFactory::withAssets(2)->ownedBy($this->signIn())->create();

        $this->get(route('clips.edit', $clip))->assertSee('Trigger smil files');
    }

    /** @test */
    public function it_list_smil_files_if_any(): void
    {
        $clip = ClipFactory::withAssets(2)->ownedBy($this->signIn())->create();

        $clip->addAsset([
            'disk'               => 'videos',
            'original_file_name' => 'camera.smil',
            'type'               => 'smil',
            'path'               => '/videos/camera.smil',
            'duration'           => '0',
            'width'              => '0',
            'height'             => '0',
        ]);

        $this->get(route('clips.edit', $clip))->assertSee('camera.smil');
    }
}
