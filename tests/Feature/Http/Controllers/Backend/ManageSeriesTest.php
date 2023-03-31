<?php

namespace Tests\Feature\Http\Controllers\Backend;

use App\Enums\Acl;
use App\Enums\OpencastWorkflowState;
use App\Http\Livewire\CommentsSection;
use App\Models\Clip;
use App\Models\Image;
use App\Models\Series;
use App\Models\User;
use App\Services\OpencastService;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class ManageSeriesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WorksWithOpencastClient;

    private OpencastService $opencastService;

    private MockHandler $mockHandler;

    private string $flashMessageName;

    private string $role = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);

        $this->flashMessageName = 'flashMessage';

        $this->role = 'moderator';
    }

    /** @test */
    public function it_shows_a_create_series_button_if_moderator_has_no_series(): void
    {
        $this->signInRole('moderator');

        $this->get(route('series.index'))->assertSee('Create new series');
    }

    /** @test */
    public function it_shows_series_information_in_index_page(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))
            ->withClips(3)
            ->withAssets(2)
            ->create();

        Clip::find(1)->addAcls(collect([Acl::PORTAL()])); //assign 'intern' acl
        Clip::find(2)->addAcls(collect([Acl::LMS()])); //assign 'lms' acl
        Clip::find(3)->addAcls(collect([Acl::LMS()])); //assign 'lms' acl

        $this->get(route('series.index'))
            ->assertSee($series->title)
            ->assertSee('portal, lms');
    }

    /** @test */
    public function it_paginates_users_series_in_dashboard_index_page(): void
    {
        $this->markTestSkipped('Livewire/Tailwind ERROR Using $this when not in object context in file');

        Series::factory(20)->create(['owner_id' => $this->signInRole($this->role)]);

        $this->get(route('series.index').'?page=2')->assertDontSee('No more series found');
    }

    /** @test */
    public function it_paginates_all_series_in_dashboard_index_page_for_admin_user(): void
    {
        $this->markTestSkipped('Livewire/Tailwind ERROR Using $this when not in object context in file');

        Series::factory(20)->create();

        $this->signInRole('admin');

        $this->get(route('series.index').'?page=2')->assertDontSee('No more series found');
    }

    /** @test */
    public function a_moderator_can_see_only_owned_series_in_index(): void
    {
        $user = $this->signInRole($this->role);

        $userSeries = Series::factory(3)->create(['owner_id' => $user->id]);

        $this->get(route('series.index'))
            ->assertSee($userSeries->first()->get()->first()->title);
    }

    /** @test */
    public function a_moderator_can_see_in_series_index_all_series_that_is_member_of(): void
    {
        $series = Series::factory()->create(['title' => 'First series']);

        $user = $this->signInRole($this->role);

        Series::factory(3)->create(['owner_id' => $user->id, 'title' => 'User series']);

        $this->get(route('series.index'))
            ->assertSee('User series')
            ->assertDontSee('First series');

        $series->addMember($user);

        $this->get(route('series.index'))->assertSee('First series');
    }

    /** @test */
    public function it_paginates_all_series_in_dashboard_index_page_for_assistant_user(): void
    {
        $this->markTestSkipped('Livewire/Tailwind ERROR Using $this when not in object context in file');

        Series::factory(20)->create();

        $this->signInRole('assistant');

        $this->get(route('series.index').'?page=2')->assertDontSee('No more series found');
    }

    /** @test */
    public function a_moderator_can_see_the_create_series_form_and_all_form_fields(): void
    {
        $this->signInRole($this->role);

        $this->get(route('series.create'))
            ->assertSee('title')
            ->assertSee('description')
            ->assertSee('presenters')
            ->assertSee('acls')
            ->assertSee('password')
            ->assertSee('is_public')
            ->assertSee('Organization');

        $this->get(route('series.create'))->assertOk()
            ->assertViewIs('backend.series.create');
    }

    /** @test */
    public function it_requires_a_title_when_creating_a_new_series(): void
    {
        $this->signInRole($this->role);

        $attributes = Series::factory()->raw(['title' => '']);

        $this->post(route('series.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function it_requires_an_organization_id_when_creating_a_new_series(): void
    {
        $this->signInRole($this->role);

        $attributes = Series::factory()->raw(['organization_id' => null]);

        $this->post(route('series.store'), $attributes)->assertSessionHasErrors('organization_id');
    }

    /** @test */
    public function it_must_have_a_strong_password_if_any(): void
    {
        $this->signInRole($this->role);

        $this->post(route('series.store', Series::factory()->raw([
            'title' => 'This is a test',
            'password' => '1234',
            'organization_id' => '1',
        ])))->assertSessionHasErrors('password');

        $this->post(route('series.store', Series::factory()->raw([
            'title' => 'This is a test',
            'password' => '1234qwER',
            'organization_id' => '1',
        ])));

        $this->assertDatabaseHas('series', ['password' => '1234qwER']);
    }

    /** @test */
    public function an_authenticated_user_is_not_allowed_to_create_new_series(): void
    {
        $this->signIn();

        $this->post(
            route('series.store'),
            [
                'title' => 'Test title',
                'description' => 'Test description',
                'organization_id' => '1',
            ]
        )->assertForbidden();
    }

    /** @test */
    public function an_assistant_is_not_allowed_to_create_new_series(): void
    {
        $this->signInRole('assistant');
        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $this->post(
            route('series.store'),
            [
                'title' => 'Test title',
                'description' => 'Test description',
                'organization_id' => '1',
            ]
        )->assertForbidden();
    }

    /** @test */
    public function a_moderator_can_create_a_series(): void
    {
        $this->signInRole($this->role);

        $this->post(
            route('series.store'),
            [
                'title' => 'Test title',
                'description' => 'Test description',
                'organization_id' => '1',
            ]
        );

        $this->assertDatabaseHas('series', ['title' => 'Test title']);
    }

    /** @test */
    public function an_admin_can_create_a_series(): void
    {
        $this->signInRole('admin');

        $this->post(
            route('series.store'),
            [
                'title' => 'Test title',
                'description' => 'Test description',
                'organization_id' => '1',
            ]
        );

        $this->assertDatabaseHas('series', ['title' => 'Test title']);
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_series_is_created(): void
    {
        $this->signInRole($this->role);

        $this->post(
            route('series.store'),
            [
                'title' => 'Test title',
                'description' => 'Test description',
                'organization_id' => '1',
            ]
        )->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function it_creates_an_opencast_series_when_new_series_is_created(): void
    {
        $this->signInRole($this->role);

        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $this->post(route('series.store'), [
            'title' => 'Series title',
            'description' => 'test',
            'organization_id' => '1',
        ]);

        $series = Series::all()->first();

        $this->assertNotNull($series->opencast_series_id);
    }

    /** @test */
    public function it_requires_a_title_creating_a_series(): void
    {
        $this->signInRole($this->role);

        $attributes = Series::factory()->raw(['title' => '']);

        $this->post(route('series.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function create_series_form_should_remember_old_values_on_validation_error(): void
    {
        $this->signInRole($this->role);

        $attributes = [
            'title' => '',
            'description' => 'test',
        ];

        $this->post(route('series.store'), $attributes);

        $this->followingRedirects();

        $this->get(route('series.create'))->assertSee($attributes);
    }

    /** @test */
    public function a_series_owner_can_view_edit_form_fields(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series),
            $this->mockEventResponse($series, OpencastWorkflowState::STOPPED)
        );

        $this->get(route('series.edit', $series))
            ->assertOk()
            ->assertViewIs('backend.series.edit')
            ->assertSee('title')
            ->assertSee('presenters')
            ->assertSee('description');
    }

    /** @test */
    public function a_series_member_can_view_edit_form_fields_and_owner_name_and_username(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();
        auth()->logout();

        $user2 = $this->signInRole('moderator');

        $this->get(route('series.edit', $series))->assertForbidden();

        $series->addMember($user2);

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, false),
            $this->mockEventResponse($series, OpencastWorkflowState::STOPPED)
        );

        $this->get(route('series.edit', $series))
            ->assertOk()
            ->assertSee($series->owner->username);
    }

    /** @test */
    public function a_moderator_cannot_view_edit_clip_form_for_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole($this->role);

        $this->get(route('series.edit', $series))->assertForbidden();
    }

    /** @test */
    public function an_admin_can_edit_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, false),
            $this->mockEventResponse($series, OpencastWorkflowState::STOPPED)
        );

        $this->signInRole('admin');

        $this->get(route('series.edit', $series))->assertOk();
    }

    /** @test */
    public function a_superadmin_can_edit_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series),
            $this->mockEventResponse($series, OpencastWorkflowState::STOPPED)
        );

        $this->signInRole('superadmin');

        $this->get(route('series.edit', $series))->assertOk();
    }

    /** @test */
    public function it_has_an_add_clips_button(): void
    {
        $this->get(route('series.edit', SeriesFactory::ownedBy($this->signInRole($this->role))->create()))
            ->assertSee('Add new clip');
    }

    /** @test */
    public function it_has_go_to_public_page_button(): void
    {
        $this->get(route('series.edit', SeriesFactory::ownedBy($this->signInRole($this->role))->create()))
            ->assertSee('Go to public page');
    }

    /** @test */
    public function edit_series_page_should_display_belonging_clips(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->withClips(2)->create();

        $this->get(route('series.edit', $series))->assertSee($series->clips()->first()->title);
    }

    /** @test */
    public function edit_series_should_display_series_image_information(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();
        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, true),
            $this->mockEventResponse($series, OpencastWorkflowState::FAILED)
        );

        $this->get(route('series.edit', $series))
            ->assertSee($series->image->description);
    }

    /** @test */
    public function edit_series_should_allow_user_to_switch_to_default_image_if_one_is_set(): void
    {
        Image::factory(2)->create();
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();
        $series->image_id = Image::find(2)->id;
        $series->save();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, true),
            $this->mockEventResponse($series, OpencastWorkflowState::FAILED)
        );

        $this->get(route('series.edit', $series))
            ->assertSee('Set Default image');

        $series->image_id = 1;
        $series->save();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, true),
            $this->mockEventResponse($series, OpencastWorkflowState::FAILED)
        );

        $this->get(route('series.edit', $series))
            ->assertDontSee('Set Default image');
    }

    /** @test */
    public function series_admin_can_select_another_image(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();
        $series->image_id = Image::find(1)->id;
        $series->save();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, true),
            $this->mockEventResponse($series, OpencastWorkflowState::FAILED)
        );

        $this->get(route('series.edit', $series))
            ->assertSee('Assign selected image');
    }

    /** @test */
    public function edit_series_page_should_display_opencast_users_rights(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, true),
            $this->mockEventResponse($series, OpencastWorkflowState::FAILED)
        );

        $this->get(route('series.edit', $series))
            ->assertViewHas(['opencastSeriesInfo'])
            ->assertSee(User::find(1)->first()->getFullNameAttribute());
    }

    /** @test */
    public function edit_series_should_display_opencast_running_events_if_any(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $runningWorkflow = $this->mockSeriesRunningWorkflowsResponse($series, true),
            $this->mockEventResponse($series, OpencastWorkflowState::FAILED)
        );

        $opencastViewData = collect(json_decode($runningWorkflow->getBody(), true));

        $this->get(route('series.edit', $series))
            ->assertViewHas(['opencastSeriesInfo'])
            ->assertSee($opencastViewData[0]['title']);
    }

    /** @test */
    public function edit_series_should_display_opencast_failed_events_if_any(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create(); //pass an empty opencast response

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockSeriesMetadata($series),
            $this->mockSeriesRunningWorkflowsResponse($series, true),
            $failedWorkflow = $this->mockEventResponse(
                $series,
                OpencastWorkflowState::FAILED
            ),
        );
        $this->mockHandler->append($failedWorkflow);

        $failedWorkflowResponse = collect(json_decode($failedWorkflow->getBody(), true));

        $this->get(route('series.edit', $series))
            ->assertViewHas(['opencastSeriesInfo'])
            ->assertSee($failedWorkflowResponse->pluck('title')->first());
    }

    /** @test */
    public function it_loads_comments_component_at_edit_page(): void
    {
        $this->mockHandler->append(new Response());
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->get(route('series.edit', $series))->assertSeeLivewire('comments-section');
    }

    /** @test */
    public function edit_series_should_display_admin_comments(): void
    {
        $this->mockHandler->append(new Response());
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->get(route('series.edit', $series))->assertSee(__('clip.frontend.comments'));

        Livewire::test(CommentsSection::class, [
            'model' => $series,
            'type' => 'backend',
        ])
            ->set('content', 'Admin series comment')
            ->call('postComment')
            ->assertSee('Comment posted successfully')
            ->assertSee('Admin series comment');
    }

    /** @test */
    public function a_series_owner_can_update_series(): void
    {
        $this->mockHandler->append(new Response());

        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->patch(route('series.edit', $series), [
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
        ]);

        $series->refresh();

        $this->assertDatabaseHas('series', [
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
        ]);

        $this->get(route('series.edit', $series))->assertSee('changed');
    }

    /** @test */
    public function it_updates_opencast_series_id_if_is_null()
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        //pass an empty opencast response
        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $this->patch(route('series.edit', $series), [
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
        ]);

        $series = $series->refresh();

        $this->assertNotNull($series->opencast_series_id);
    }

    /** @test */
    public function it_shows_create_oc_series_button_if_no_series_exist_in_opencast(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))
            ->withOpencastID()
            ->create();

        $this->mockHandler->append(
            $this->mockHealthResponse(),
            $this->mockNoSeriesFoundResponse(),
            $this->mockSeriesRunningWorkflowsResponse($series, true),
            $this->mockEventResponse($series, OpencastWorkflowState::FAILED)
        );

        $this->get(route('series.edit', $series))->assertSee('Create Opencast series for this object');
    }

    /** @test */
    public function a_moderator_cannot_update_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole($this->role);

        $this->patch(route('series.edit', $series), [
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
        ])->assertForbidden();

        $this->assertDatabaseMissing('series', ['title' => 'changed']);
    }

    /** @test */
    public function an_admin_user_can_update_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole('admin');

        //pass an empty opencast respons
        $this->mockHandler->append($this->mockSeriesRunningWorkflowsResponse($series, false));

        $this->patch(route('series.edit', $series), [
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
        ]);

        $this->assertDatabaseHas('series', ['title' => 'changed']);
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_series_is_updated()
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->patch(route('series.edit', $series), [
            'title' => 'changed',
            'description' => 'changed',
            'organization_id' => '1',
        ])->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function a_moderator_cannot_delete_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole($this->role);

        $this->delete(route('series.edit', $series))->assertForbidden();

        $this->assertDatabaseHas('series', $series->only('id'));
    }

    /** @test */
    public function an_assistant_is_not_allowed_to_delete_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole('assistant');

        $this->delete(route('series.edit', $series))->assertForbidden();

        $this->assertDatabaseHas('series', $series->only('id'));
    }

    /** @test */
    public function an_admin_user_can_delete_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInRole('admin');

        $this->followingRedirects()->delete(route('series.edit', $series))->assertOk();

        $this->assertDatabaseMissing('series', $series->only('id'));
    }

    /** @test */
    public function a_series_owner_can_delete_series(): void
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->delete(route('series.edit', $series));

        $this->assertDatabaseMissing('series', $series->only('id'));
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_series_is_deleted()
    {
        $series = SeriesFactory::ownedBy($this->signInRole($this->role))->create();

        $this->delete(route('series.edit', $series))->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function it_shows_series_owner_if_user_is_admin(): void
    {
        $series = Series::factory()->create();

        $this->signInRole('admin');

        $this->get(route('series.edit', $series))->assertSee($series->owner->first_name);
    }
}
