<?php

namespace Tests\Feature\Backend;

use App\Enums\Content;
use App\Models\Clip;
use App\Models\Presenter;
use App\Models\Tag;
use App\Services\OpencastService;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class ManageClipsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WorksWithOpencastClient;

    private OpencastService $opencastService;

    private string $flashMessageName;

    private string $role = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->flashMessageName = 'flashMessage';

        $this->role = 'moderator';
    }

    /** @test */
    public function it_shows_a_create_clip_button_if_moderator_has_no_series(): void
    {
        $this->signInRole('moderator');

        $this->get(route('clips.index'))->assertSee('Create new clip');
    }

    /** @test */
    public function it_load_the_editor_on_clip_form_textarea(): void
    {
        $this->signInRole('admin');

        $this->get(route('clips.create'))->assertSee('trix-editor');
        $this->get(route('clips.edit', ClipFactory::create()))->assertSee('trix-editor');
    }

    /** @test */
    public function it_shows_all_clips_in_index_page_for_assistant(): void
    {
        Clip::factory(10)->create();

        $this->signInRole('assistant');

        $this->get(route('clips.index'))
            ->assertOk()
            ->assertViewIs('backend.clips.index')
            ->assertViewHas('clips')
            ->assertSee(Clip::all()->first()->title);
    }

    /** @test */
    public function it_shows_all_clips_in_index_page_for_admins(): void
    {
        Clip::factory(10)->create();

        $this->signInRole('admin');

        $this->get(route('clips.index'))
            ->assertOk()
            ->assertViewIs('backend.clips.index')
            ->assertViewHas('clips')
            ->assertSee(Clip::all()->first()->title);
    }

    /** @test */
    public function it_paginates_users_clips_in_dashboard_index_page(): void
    {
        $this->markTestSkipped('Livewire/Tailwind Using $this when not in object context in file');

        Clip::factory(20)->create(['owner_id' => $this->signInRole($this->role)]);

        $this->get(route('clips.index').'?page=2')->assertDontSee('You have no series yet');
    }

    /** @test */
    public function it_paginates_all_clips_in_dashboard_index_page_for_admin_user(): void
    {
        $this->markTestSkipped('Livewire/Tailwind Using $this when not in object context in file');

        Clip::factory(20)->create();

        $this->signInRole('admin');

        $this->get(route('clips.index').'?page=2')->assertDontSee('You have no series yet');
    }

    /** @test */
    public function it_requires_a_title_creating_a_new_clip(): void
    {
        $this->signInRole($this->role);

        $attributes = Clip::factory()->raw(['title' => '']);

        $this->post(route('clips.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function it_requires_a_recording_date_creating_a_new_clip(): void
    {
        $this->signInRole($this->role);

        $attributes = Clip::factory()->raw(['recording_date' => '']);

        $this->post(route('clips.store'), $attributes)->assertSessionHasErrors('recording_date');
    }

    /** @test */
    public function it_requires_a_semester_creating_a_new_clip(): void
    {
        $this->signInRole($this->role);

        $attributes = Clip::factory()->raw(['semester_id' => '']);

        $this->post(route('clips.store'), $attributes)->assertSessionHasErrors('semester_id');
    }

    /** @test */
    public function it_must_have_a_strong_password_if_any(): void
    {
        $this->signInRole($this->role);

        $this->post(route('clips.store', Clip::factory()->raw([
            'title' => 'This is a test',
            'password' => '1234',
        ])))->assertSessionHasErrors('password');

        $this->followingRedirects()->post(route('clips.store', Clip::factory()->raw([
            'title' => 'This is a test',
            'password' => '1234qwER',
        ])))->assertOk();
    }

    /** @test */
    public function an_authenticated_user_is_not_allowed_to_create_new_clip(): void
    {
        $this->signIn();

        $this->post(route('clips.store'), Clip::factory()->raw())->assertForbidden();
    }

    /** @test */
    public function a_moderator_can_load_create_clip_view(): void
    {
        $this->signInRole($this->role);

        $this->get(route('clips.create'))->assertOk()->assertViewIs('backend.clips.create');
    }

    /** @test */
    public function an_assistant_can_load_create_clip_view(): void
    {
        $this->signInRole('assistant');

        $this->get(route('clips.create'))->assertOk()->assertViewIs('backend.clips.create');
    }

    /** @test */
    public function an_admin_can_load_create_clip_view(): void
    {
        $this->signInRole('admin');

        $this->get(route('clips.create'))->assertOk()->assertViewIs('backend.clips.create');
    }

    /** @test */
    public function a_moderator_can_create_a_clip(): void
    {
        $this->signInRole($this->role);

        $this->followingRedirects()
            ->post(route('clips.store'), $attributes = Clip::factory()->raw())
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function an_admin_can_create_a_clip(): void
    {
        $this->signInRole('admin');

        $this->followingRedirects()
            ->post(route('clips.store'), $attributes = Clip::factory()->raw())
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function it_shows_an_error_if_presenters_array_has_no_integer_values(): void
    {
        $this->signInRole($this->role);

        $data = Clip::factory()->raw(['presenters' => ['1.3', 'test']]);

        $this->post(route('clips.store', $data))->assertSessionHasErrors('presenters.*');
    }

    /** @test */
    public function a_moderator_can_see_the_create_clip_form_and_all_form_fields(): void
    {
        $this->signInRole($this->role);

        $this->get(route('clips.create'))
            ->assertSee('title')
            ->assertSee('description')
            ->assertSee('recording_date')
            ->assertSee('presenters')
            ->assertSee('organization')
            ->assertSee('language')
            ->assertSee('context')
            ->assertSee('format')
            ->assertSee('type')
            ->assertSee('tags')
            ->assertSee('acls')
            ->assertSee('semester')
            ->assertSee('is_public');

        $this->get(route('clips.create'))->assertOk()
            ->assertViewIs('backend.clips.create');
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_clip_is_created(): void
    {
        $this->signInRole($this->role);

        $this->post(route('clips.store'), Clip::factory()->raw())->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function a_moderator_can_view_the_edit_clip_form_add_all_form_fields(): void
    {
        $this->withoutExceptionHandling();
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->get($clip->adminPath())->assertOk();

        $this->get($clip->adminPath())->assertSee('title')
            ->assertSee('description')
            ->assertSee('tags')
            ->assertSee('organization')
            ->assertSee('recording_date')
            ->assertSee('language')
            ->assertSee('context')
            ->assertSee('format')
            ->assertSee('type')
            ->assertSee('tags')
            ->assertSee('presenters')
            ->assertSee('semester')
            ->assertSee('is_public')
            ->assertSee('acls');
    }

    /** @test */
    public function it_lists_whether_a_clip_belongs_to_a_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->withClips(2)->create();

        $this->get(route('clips.edit', $series->clips()->first()))->assertSee($series->title);
    }

    /** @test */
    public function a_moderator_cannot_view_clip_edit_form_for_not_owned_clips(): void
    {
        $clip = ClipFactory::create();

        $this->signInRole($this->role);

        $this->get($clip->adminPath())->assertForbidden();
    }

    /** @test */
    public function an_admin_can_edit_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInRole('admin');

        $this->get($clip->adminPath())->assertOk();
    }

    /** @test */
    public function a_superadmin_can_edit_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInRole('superadmin');

        $this->get($clip->adminPath())->assertOk();
    }

    /** @test */
    public function a_moderator_can_create_a_clip_with_tags(): void
    {
        $this->signInRole($this->role);

        $attributes = Clip::factory()->raw([
            'tags' => ['php', 'example', 'oop'],
        ]);

        $this->followingRedirects()->post(route('clips.store'), $attributes)->assertSee($attributes['tags']);

        $clip = Clip::first();

        $this->assertDatabaseCount('tags', 3);

        $this->assertEquals(3, $clip->tags()->count());
    }

    /** @test */
    public function a_moderator_can_create_a_clip_with_presenters(): void
    {
        Presenter::factory(2)->create();
        $presenter1 = Presenter::find(1);
        $presenter2 = Presenter::find(2);
        $this->signInRole($this->role);

        $attributes = Clip::factory()->raw([
            'presenters' => [$presenter1->id, $presenter2->id],
        ]);

        $this->post(route('clips.store'), $attributes);

        $clip = Clip::first();

        $this->assertDatabaseCount('presentables', 2);

        $this->assertEquals(2, $clip->presenters()->count());
    }

    /** @test */
    public function a_moderator_can_create_a_clip_with_acls(): void
    {
        $this->signInRole($this->role);

        $attributes = Clip::factory()->raw([
            'acls' => ['1', '2'],
        ]);

        $this->post(route('clips.store'), $attributes);

        $clip = Clip::first();

        $this->assertEquals(2, $clip->acls()->count());
    }

    /** @test */
    public function a_moderator_can_remove_clip_tags(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $clip->tags()->sync(Tag::factory()->create());

        $this->patch($clip->adminPath(), [
            'episode' => '1',
            'title' => 'changed',
            'description' => 'changed',
            'recording_date' => now(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'tags' => [],
            'semester_id' => '1',
        ]);

        $this->assertEquals(0, $clip->tags()->count());
    }

    /** @test */
    public function a_moderator_can_update_clip_tags(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $tag = Tag::factory()->create();

        $clip->tags()->sync(Tag::factory()->create());

        $this->patch($clip->adminPath(), [
            'episode' => '1',
            'title' => 'changed',
            'description' => 'changed',
            'recording_date' => now(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'tags' => [$tag->name, 'another tag'],
            'semester_id' => '1',
        ]);

        $this->assertEquals(2, $clip->tags()->count());
    }

    /** @test */
    public function create_clip_form_should_remember_old_values_on_validation_error(): void
    {
        $this->signInRole($this->role);

        $attributes = [
            'title' => 'Clip title',
            'description' => $this->faker->sentence(500),
            'recording_date' => now()->toDateString(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'semester_id' => '1',
        ];

        $this->post(route('clips.store'), $attributes);

        $this->followingRedirects();

        $this->get(route('clips.create'))->assertSee($attributes);
    }

    /** @test */
    public function a_moderator_can_update_his_clip(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->get($clip->path())->assertSee($clip->title);

        $this->patch($clip->adminPath(), [
            'episode' => '1',
            'title' => 'changed',
            'description' => 'changed',
            'recording_date' => now(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'semester_id' => '1',
        ]);

        $clip = $clip->refresh();

        $this->assertDatabaseHas('clips', [
            'episode' => '1',
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
            'semester_id' => '1',
        ]);

        $this->get($clip->adminPath())->assertSee('changed');
    }

    /** @test */
    public function a_moderator_cannot_update_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInRole($this->role);

        $attributes = [
            'episode' => '1',
            'title' => 'changed',
            'description' => 'changed',
            'recording_date' => now(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'semester_id' => '1',
        ];

        $this->patch($clip->adminPath(), $attributes)->assertForbidden();

        $this->assertDatabaseMissing('clips', $attributes);
    }

    /** @test */
    public function an_admin_user_can_update_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInRole('admin');

        $attributes = [
            'episode' => '1',
            'title' => 'changed',
            'description' => 'changed',
            'recording_date' => now(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'semester_id' => '1',
        ];

        $this->followingRedirects()->patch($clip->adminPath(), $attributes)->assertOk();

        $this->assertDatabaseHas('clips', $attributes);
    }

    /** @test */
    public function it_updates_clip_slug_if_title_is_changed(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->patch($clip->adminPath(), [
            'episode' => '1',
            'title' => 'Title changed',
            'recording_date' => now(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'semester_id' => '1',
        ]);

        $clip->refresh();

        $this->assertEquals('title-changed', $clip->slug);
    }

    /** @test */
    public function it_does_not_update_clip_slug_if_title_is_not_changed()
    {
        $this->signInRole($this->role);

        $this->post(route('clips.store'), [
            'episode' => '1',
            'title' => 'Test clip',
            'recording_date' => now(),
            'description' => 'test',
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'semester_id' => '1',
        ]);

        $clip = Clip::find(1);

        $this->patch($clip->adminPath(), ['episode' => '2', 'title' => 'Test clip', 'description' => 'test']);

        $clip->refresh();

        $this->assertEquals('test-clip', $clip->slug);
    }

    /** @test */
    public function it_has_an_upload_button_in_edit_form(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->get(route('clips.edit', $clip))->assertSee('Upload');
    }

    /** @test */
    public function it_shows_an_lms_test_link_if_clip_has_an_lms_acl_and_user_is_admin(): void
    {
        $userClip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->get(route('clips.edit', $userClip))->assertDontSee('LMS Test Link');

        $adminClip = ClipFactory::ownedBy($this->signInRole('admin'))->create();

        $this->get(route('clips.edit', $adminClip))->assertDontSee('LMS Test Link');

        $adminClip->addAcls(collect(['4']));

        $this->get(route('clips.edit', $adminClip))->assertSee('LMS Test Link');
    }

    /** @test */
    public function it_has_opencast_action_buttons_if_opencast_server_exists(): void
    {
        $mockHandler = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);

        $mockHandler->append($this->mockHealthResponse());

        $this->get(route('clips.edit', ClipFactory::ownedBy($this->signInRole($this->role))->create()))
            ->assertSee('Ingest to Opencast')
            ->assertSee('Transfer files from Opencast');
    }

    /** @test */
    public function it_hides_opencast_action_buttons_if_opencast_server_does_not_exists(): void
    {
        $mockHandler = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);

        $mockHandler->append(new Response());

        $this->get(route('clips.edit', ClipFactory::ownedBy($this->signInRole($this->role))->create()))
            ->assertDontSee('Ingest to Opencast')
            ->assertDontSee('Transfer files from Opencast');
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_clip_is_updated()
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->patch($clip->adminPath(), [
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
        ])->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function a_moderator_cannot_delete_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInRole($this->role);

        $this->delete($clip->adminPath())->assertForbidden();

        $this->assertDatabaseHas('clips', $clip->only('id'));
    }

    /** @test */
    public function an_admin_user_can_delete_a_not_owned_clip(): void
    {
        $clip = ClipFactory::create();

        $this->signInRole('admin');

        $this->followingRedirects()->delete($clip->adminPath())->assertOk();

        $this->assertDatabaseMissing('clips', $clip->only('id'));
    }

    /** @test */
    public function a_moderator_can_delete_owned_clip(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->delete($clip->adminPath())->assertRedirect(route('clips.index'));

        $this->assertDatabaseMissing('clips', $clip->only('id'));
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_clip_is_deleted(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->delete($clip->adminPath())->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function it_can_toggle_comments(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create();

        $this->get($clip->path())->assertDontSee('Comments');

        $this->patch(route('clips.update', $clip), [
            'title' => $clip->title,
            'episode' => $clip->episode,
            'recording_date' => now(),
            'organization_id' => '1',
            'language_id' => '1',
            'context_id' => '1',
            'format_id' => '1',
            'type_id' => '1',
            'semester_id' => '1',
            'allow_comments' => 'on',
        ]);

        $this->get($clip->path())->assertSee('Comments');
    }

    /** @test */
    public function it_displays_previous_next_clip_id_links(): void
    {
        $this->signInRole('admin');

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
        $this->signInRole('admin');

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
        $this->signInRole('admin');

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
        $clip = ClipFactory::withAssets(2)->ownedBy($this->signInRole($this->role))->create();

        $this->get(route('clips.edit', $clip))->assertSee('Trigger smil files');
    }

    /** @test */
    public function it_list_smil_files_if_any(): void
    {
        $clip = ClipFactory::withAssets(2)->ownedBy($this->signInRole($this->role))->create();

        $clip->addAsset([
            'disk' => 'videos',
            'original_file_name' => 'camera.smil',
            'type' => Content::SMIL(),
            'path' => '/videos/camera.smil',
            'guid' => Str::uuid(),
            'duration' => '0',
            'width' => '0',
            'height' => '0',
        ]);

        $this->get(route('clips.edit', $clip))->assertSee('camera.smil');
    }

    /** @test */
    public function it_list_audio_files_if_any(): void
    {
        $clip = ClipFactory::withAssets(2)->ownedBy($this->signInRole($this->role))->create();

        $clip->addAsset([
            'disk' => 'videos',
            'original_file_name' => 'audio.mp3',
            'type' => Content::AUDIO(),
            'path' => '/videos/'.$clip->folder_id.'/audio.mp3',
            'guid' => Str::uuid(),
            'duration' => '120',
            'width' => '0',
            'height' => '0',
        ]);

        $this->get(route('clips.edit', $clip))->assertSee('audio.mp3');
    }

    /** @test */
    public function clip_edit_page_has_a_assign_series_option_if_clip_has_no_series(): void
    {
        $clip = ClipFactory::ownedBy($this->signInRole($this->role))->create(['series_id' => 1]);

        $this->get(route('clips.edit', $clip))->assertDontSee('Assign series');

        $clip = ClipFactory::create();

        $this->get(route('clips.edit', $clip))->assertSee('Assign series ');
    }
}
