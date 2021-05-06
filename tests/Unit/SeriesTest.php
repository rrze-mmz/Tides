<?php

namespace Tests\Unit;

use App\Models\Clip;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\SeriesFactory;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class SeriesTest extends TestCase {
    use RefreshDatabase, WorksWithOpencastClient;

    protected Series $series;

    protected function setUp(): void
    {
        parent::setUp();

        $this->series = Series::factory()->create();
    }

    /** @test */
    public function it_has_a_path(): void
    {
        $this->assertEquals('/series/' . $this->series->slug, $this->series->path());
    }

    /** @test */
    public function it_has_an_admin_path(): void
    {
        $this->assertEquals('/admin/series/' . $this->series->slug, $this->series->adminPath());
    }

    /** @test */
    public function it_has_a_slug_route(): void
    {
        $this->get($this->series->path())->assertStatus(200);
    }

    /** @test */
    public function it_has_a_unique_slug(): void
    {
        $seriesA = Series::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $seriesB = Series::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $this->assertNotEquals($seriesA->slug, $seriesB->slug);
    }

    /** @test */
    public function it_has_many_clips(): void
    {
        Clip::factory(2)->create(['series_id' => $this->series->id]);

        $this->assertEquals(2, $this->series->clips()->count());
    }

    /** @test */
    public function it_can_add_a_clip(): void
    {
        $this->signIn();

        $this->assertInstanceOf(Clip::class, $this->series->addClip([
            'title'       => 'a clip',
            'slug'        => 'a-clip',
            'tags'        => [],
            'description' => 'clip description',
        ]));
    }

    /** @test */
    public function it_updates_opencast_series_id(): void
    {
        $series = SeriesFactory::create();

        $series->updateOpencastSeriesId($this->mockCreateSeriesResponse());

        $this->assertNotNull($series->opencast_series_id);
    }
}
