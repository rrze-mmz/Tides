<?php

namespace Tests\Feature\Backend;

use App\Http\Livewire\ActivitiesDataTable;
use App\Models\Activity;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Presenter;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_triggers_an_activity_on_series_create_event(): void
    {
        $series = Series::factory()->create();

        $this->assertDatabaseHas('activities', ['object_id' => $series->id]);
    }

    /** @test */
    public function it_triggers_an_activity_on_series_update(): void
    {
        $series = Series::factory()->create();

        $this->assertDatabaseHas('activities', ['change_message' => 'created series']);

        $series->title = 'Test';
        $series->save();

        $this->assertDatabaseHas('activities', ['change_message' => 'updated series']);
    }

    /** @test */
    public function it_triggers_an_activity_on_series_delete(): void
    {
        $series = Series::factory()->create();
        $this->assertDatabaseHas('activities', ['change_message' => 'created series']);

        $series->delete();

        $this->assertDatabaseHas('activities', ['change_message' => 'deleted series']);
    }

    /** @test */
    public function it_returns_all_activities_for_a_given_series(): void
    {
        $series = Series::factory()->create();
        $series->title = 'Test';
        $series->save();
        $anotherSeries = Series::factory()->create();

        $this->assertEquals(2, $series->activities()->count());
        $this->assertEquals(1, $anotherSeries->activities()->count());
    }

    /** @test */
    public function it_triggers_an_activity_on_clip_create(): void
    {
        Clip::factory()->create();

        $this->assertDatabaseHas('activities', ['change_message' => 'created clip']);
    }

    /** @test */
    public function it_triggers_an_activity_on_clip_update(): void
    {
        $clip = Clip::factory()->create();
        $this->assertDatabaseHas('activities', ['change_message' => 'created clip']);

        $clip->title = 'Test';
        $clip->save();

        $this->assertDatabaseHas('activities', ['change_message' => 'updated clip']);
    }

    /** @test */
    public function it_triggers_an_activity_on_clip_delete(): void
    {
        $clip = Clip::factory()->create();
        $this->assertDatabaseHas('activities', ['change_message' => 'created clip']);

        $clip->delete();

        $this->assertDatabaseHas('activities', ['change_message' => 'deleted clip']);
    }

    /** @test */
    public function it_returns_all_activities_for_a_given_clip(): void
    {
        $clip = Clip::factory()->create();
        $clip->title = 'Test';
        $clip->save();
        $anotherClip = Clip::factory()->create();

        $this->assertEquals(3, $clip->activities()->count());
        $this->assertEquals(2, $anotherClip->activities()->count());
    }

    /** @test */
    public function it_triggers_an_activity_on_presenter_create(): void
    {
        Presenter::factory()->create();

        $this->assertDatabaseHas('activities', ['change_message' => 'created presenter']);
    }

    /** @test */
    public function it_triggers_an_activity_on_user_create(): void
    {
        User::factory()->create();

        $this->assertDatabaseHas('activities', ['change_message' => 'created user']);
    }

    /** @test */
    public function it_triggers_an_activity_on_asset_create(): void
    {
        Asset::factory()->create();

        $this->assertDatabaseHas('activities', ['change_message' => 'created asset']);
    }

    public function it_renders_a_datatable_for_activities(): void
    {
        $this->get(route('activities.index'))->assertStatus(200);
    }

    public function it_contains_activities_livewire_component_on_index_page(): void
    {
        $this->get(route('activities.index'))->assertSeeLivewire('activities-data-table');
    }

    /** @test */
    public function it_has_a_series_checkbox_in_index_activities_data_table_that_filter_series(): void
    {
        $seriesActivity = Activity::factory()->create([
            'content_type'   => 'series',
            'change_message' => 'create series'
        ]);
        $clipActivity = Activity::factory()->create([
            'content_type'   => 'clip',
            'change_message' => 'create clip'
        ]);

        Livewire::test(ActivitiesDataTable::class)
            ->assertSee($seriesActivity->change_message)
            ->assertSee($clipActivity->change_message)
            ->set('series', true)
            ->assertSee($seriesActivity->change_message)
            ->assertDontSee($clipActivity->change_message);
    }

    /** @test */
    public function it_can_search_for_a_change_message_in_index_activities_data_table(): void
    {
        $seriesActivity = Activity::factory()->create([
            'content_type'   => 'series',
            'change_message' => 'create series'
        ]);
        $clipActivity = Activity::factory()->create([
            'content_type'   => 'clip',
            'change_message' => 'update clip'
        ]);
        Livewire::test(ActivitiesDataTable::class)
            ->set('search', 'update')
            ->assertSee($clipActivity->change_message)
            ->assertDontSee($seriesActivity->change_message);
    }

    /** @test */
    public function it_can_sort_by_content_type_in_index_activities_data_table(): void
    {
        $articleActivity = Activity::factory()->create([
            'content_type'   => 'article',
            'change_message' => 'updates article'
        ]);
        $seriesActivity = Activity::factory()->create([
            'content_type'   => 'series',
            'change_message' => 'create series'
        ]);
        $clipActivity = Activity::factory()->create([
            'content_type'   => 'clip',
            'change_message' => 'update clip'
        ]);

        Livewire::test(ActivitiesDataTable::class)
            ->call('sortBy', 'content_type')
            ->call('sortBy', 'content_type')
            ->assertSeeInOrder([
                $seriesActivity->content_type, $clipActivity->content_type, $articleActivity->content_type
            ]);
    }
}
