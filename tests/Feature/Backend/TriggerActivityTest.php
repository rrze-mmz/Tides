<?php

namespace Tests\Feature\Backend;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\Presenter;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
