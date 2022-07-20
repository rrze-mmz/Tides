<?php

namespace Tests\Unit;

use App\Models\Clip;
use App\Models\Series;
use App\Models\User;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
        $this->assertEquals('/series/'.$this->series->slug, $this->series->path());
    }

    /** @test */
    public function it_has_an_admin_path(): void
    {
        $this->assertEquals('/admin/series/'.$this->series->slug, $this->series->adminPath());
    }

    /** @test */
    public function it_has_a_slug_route(): void
    {
        $this->assertEquals('/series/'.Str::slug($this->series->title), $this->series->path());
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
        $this->assertInstanceOf(HasMany::class, $this->series->clips());
    }

    /** @test */
    public function it_has_many_chapters(): void
    {
        $this->assertInstanceOf(HasMany::class, $this->series->chapters());
    }

    /** @test */
    public function it_has_many_members(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->series->members());
    }

    /** @test */
    public function it_has_an_add_member_function_for_membership(): void
    {
        $this->assertInstanceOf(User::class, $this->series->addMember(User::factory()->create()));
    }

    /** @test */
    public function it_han_a_remove_member_function_for_membership(): void
    {
        $this->assertInstanceOf(User::class, $this->series->removeMember(User::factory()->create()));
    }

    /** @test */
    public function it_has_many_presenters_using_presentable_trait(): void
    {
        $this->assertInstanceOf(MorphToMany::class, $this->series->presenters());
    }

    /** @test */
    public function it_has_many_documents_using_documentable_trait(): void
    {
        $this->assertInstanceOf(MorphToMany::class, $this->series->documents());
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
            'title' => 'a clip',
            'slug' => 'a-clip',
            'tags' => [],
            'description' => 'clip description',
            'semester_id' => '1',
        ]));
    }

    /** @test */
    public function it_can_reorder_clips_based_on_an_array_of_episodes(): void
    {
        Clip::factory(2)->create(['series_id' => $this->series->id]);

        $this->assertInstanceOf(Series::class, $this->series->reorderClips(collect([
            1 => '3',
            2 => '1',
        ])));
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

    /** @test */
    public function it_has_a_scope_to_fetch_clips_with_assets(): void
    {
        $this->assertInstanceOf(Builder::class, Series::hasClipsWithAssets());
    }

    /** @test */
    public function series_owner_can_be_null(): void
    {
        $user = User::factory()->create();

        $series = $user->series()->create(['title' => 'test', 'slug' => 'test']);

        $user->delete();

        $series = Series::find($series->id);

        $this->assertNull($series->owner_id);
    }

    /** @test */
    public function it_has_an_opencast_series_id_scope(): void
    {
        $this->assertInstanceOf(Builder::class, Series::hasOpencastSeriesID());
    }

    /** @test */
    public function it_resolves_also_id_in_route(): void
    {
        $this->get('series/'.$this->series->id)->assertStatus(403);
        $this->get(route('frontend.series.show', $this->series->id))->assertStatus(403);
        $this->get('/series/535')->assertStatus(404);
    }
}
