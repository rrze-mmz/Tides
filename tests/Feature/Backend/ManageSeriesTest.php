<?php

namespace Tests\Feature\Backend;

use App\Models\Clip;
use App\Models\Series;
use App\Services\OpencastService;
use Facades\Tests\Setup\SeriesFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class ManageSeriesTest extends TestCase {
    use RefreshDatabase, WithFaker, WorksWithOpencastClient;

    private OpencastService $opencastService;
    private MockHandler $mockHandler;
    private string $flashMessageName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = $this->swapOpencastClient();

        $this->opencastService = app(OpencastService::class);

        $this->flashMessageName = 'flashMessage';
    }

    /** @test */
    public function it_shows_series_information_in_index_page(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())
            ->withClips(3)
            ->create();

        Clip::find(1)->addAcls(collect([1])); //assign 'intern' acl
        Clip::find(2)->addAcls(collect([2])); //assign 'lms' acl
        Clip::find(3)->addAcls(collect([2])); //assign 'lms' acl


        $this->get(route('series.index'))
            ->assertSee($series->title)
            ->assertSee('intern, lms');
    }

    /** @test */
    public function it_paginates_users_series_in_dashboard_index_page(): void
    {
        Series::factory(20)->create(['owner_id' => $this->signIn()]);

        $this->get(route('series.index') . '?page=2')->assertDontSee('You have no series yet');
    }

    /** @test */
    public function it_paginates_all_series_in_dashboard_index_page_for_admin_user(): void
    {
        Series::factory(20)->create();

        $this->signInAdmin();

        $this->get(route('series.index') . '?page=2')->assertDontSee('You have no series yet');

    }

    /** @test */
    public function an_authenticated_user_can_see_the_create_series_form_and_all_form_fields(): void
    {
        $this->signIn();

        $this->get(route('series.create'))
            ->assertSee('title')
            ->assertSee('description')
            ->assertSee('acls')
            ->assertSee('password');

        $this->get(route('series.create'))->assertStatus(200)
            ->assertViewIs('backend.series.create');
    }

    /** @test */
    public function it_requires_a_title_when_creating_a_new_series(): void
    {
        $this->signIn();

        $attributes = Series::factory()->raw(['title' => '']);

        $this->post(route('series.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function it_must_have_a_strong_password_if_any(): void
    {
        $this->signIn();


        $this->post(route('series.store', Series::factory()->raw([
                        'title'    => 'This is a test',
                        'password' => '1234',
                        ])
        ))->assertSessionHasErrors('password');

        $this->post(route('series.store', Series::factory()->raw([
                        'title'    => 'This is a test',
                        'password' => '1234qwER',
                        ])
        ));

        $this->assertDatabaseHas('series', ['password'=> '1234qwER']);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_series(): void
    {
        $this->signIn();

        $this->post(route('series.store'),
            [
                'title'       => 'Test title',
                'description' => 'Test description'
            ]
        );

        $this->assertDatabaseHas('series', ['title' => 'Test title']);
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_series_is_created(): void
    {
        $this->signIn();

        $this->post(route('series.store'),
            [
                'title'       => 'Test title',
                'description' => 'Test description'
            ]
        )->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function it_creates_an_opencast_series_when_new_series_is_created(): void
    {
        $this->signIn();

        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $this->post(route('series.store'), [
            'title'       => 'Series title',
            'description' => 'test'
        ]);

        $series = Series::all()->first();

        $this->assertNotNull($series->opencast_series_id);
    }

    /** @test */
    public function it_requires_a_title_creating_a_series(): void
    {
        $this->signIn();

        $attributes = Series::factory()->raw(['title' => '']);

        $this->post(route('series.store'), $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function create_series_form_should_remember_old_values_on_validation_error(): void
    {
        $this->signIn();

        $attributes = [
            'title'       => '',
            'description' => 'test'
        ];

        $this->post(route('series.store'), $attributes);

        $this->followingRedirects();

        $this->get(route('series.create'))->assertSee($attributes);
    }

    /** @test */
    public function a_series_owner_can_view_edit_form_fields(): void
    {
        $this->withoutExceptionHandling();
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->mockHandler->append($this->mockSeriesRunningWorkflowsResponse($series, false));

        $this->get($series->adminPath())
            ->assertStatus(200)
            ->assertSee('title')
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

        $this->mockHandler->append($this->mockSeriesRunningWorkflowsResponse($series, false));

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
        $this->get(route('series.edit', SeriesFactory::ownedBy($this->signIn())->create()))
            ->assertSee('Go to public page');
    }

    /** @test */
    public function edit_series_page_should_display_belonging_clips(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->withClips(2)->create();

        $this->get(route('series.edit', $series))->assertSee($series->clips()->first()->title);
    }

    /** @test */
    public function edit_series_should_display_opencast_running_events_if_any(): void
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        //pass an empty opencast response
        $mockData = $this->mockSeriesRunningWorkflowsResponse($series, true);

        $this->mockHandler->append($mockData);

        $opencastViewData = collect(json_decode($mockData->getBody(), true));

        $this->get(route('series.edit', $series))
            ->assertViewHas(['opencastSeriesRunningWorkflows'])
            ->assertSee($opencastViewData['workflows']['workflow']['0']['mediapackage']['title']);
    }

    /** @test */
    public function a_series_owner_can_update_series(): void
    {
        $this->mockHandler->append(new Response());

        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->patch($series->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed'
        ]);

        $series->refresh();

        $this->assertDatabaseHas('series', [
            'title'       => 'changed',
            'description' => 'changed',
        ]);

        $this->get($series->adminPath())->assertSee('changed');
    }

    /** @test */
    public function it_updates_opencast_series_id_if_is_null()
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        //pass an empty opencast response
        $this->mockHandler->append($this->mockCreateSeriesResponse());

        $this->patch($series->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed'
        ]);

        $series = $series->refresh();

        $this->assertNotNull($series->opencast_series_id);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signIn();

        $this->patch($series->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed'
        ])->assertStatus(403);

        $this->assertDatabaseMissing('series', ['title' => 'changed']);
    }

    /** @test */
    public function an_admin_user_can_update_a_not_owned_series(): void
    {
        $series = SeriesFactory::create();

        $this->signInAdmin();

        //pass an empty opencast respons
        $this->mockHandler->append($this->mockSeriesRunningWorkflowsResponse($series, false));

        $this->patch($series->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed'
        ]);

        $this->assertDatabaseHas('series', ['title' => 'changed']);
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_series_is_updated()
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->patch($series->adminPath(), [
            'title'       => 'changed',
            'description' => 'changed'
        ])->assertSessionHas($this->flashMessageName);
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

        $this->delete($series->adminPath());

        $this->assertDatabaseMissing('series', $series->only('id'));
    }

    /** @test */
    public function it_shows_a_flash_message_when_a_series_is_deleted()
    {
        $series = SeriesFactory::ownedBy($this->signIn())->create();

        $this->delete($series->adminPath())->assertSessionHas($this->flashMessageName);
    }

    /** @test */
    public function it_shows_series_owner_if_user_is_admin(): void
    {
        $series = Series::factory()->create();

        $this->signInAdmin();

        $this->get(route('series.edit', $series))->assertSee($series->owner->name);
    }
}
