<?php

namespace Tests\Unit;

use App\Models\Clip;
use App\Models\Presenter;
use App\Models\Series;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Setup\WorksWithOpencastClient;
use Tests\TestCase;

class SeriesTest extends TestCase
{
    use RefreshDatabase;
    use WorksWithOpencastClient;

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
        $this->assertEquals('/series/' . Str::slug($this->series->title), $this->series->path());
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
    public function it_fetches_the_latest_clip(): void
    {
        $this->assertInstanceOf(HasOne::class, $this->series->latestClip());
    }

    /** @test */
    public function it_has_one_organization_unit(): void
    {
        $this->assertInstanceOf(HasOne::class, $this->series->organization());
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
            'semester_id' => '1',
        ]));
    }

    /** @test */
    public function it_updates_opencast_series_id(): void
    {
        $series = SeriesFactory::create();

        $series->updateOpencastSeriesId($this->mockCreateSeriesResponse());

        $this->assertNotNull($series->opencast_series_id);
    }

    /** @test */
    public function it_has_a_public_scope(): void
    {
        $this->assertInstanceOf(Builder::class, Series::isPublic());
    }
}
