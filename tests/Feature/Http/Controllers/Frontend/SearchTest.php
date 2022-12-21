<?php

namespace Tests\Feature\Http\Controllers\Frontend;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use App\Services\ElasticsearchService;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Setup\WorksWithElasticsearchClient;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WorksWithElasticsearchClient;

    protected Model $clip;

    private ElasticsearchService $elasticsearchService;

    private MockHandler $mockHandler;

    /*
    /   Set up the test
    /   Create a clip with an asset
    */
    protected function setUp(): void
    {
        parent::setUp();

        $this->clip = Clip::factory()->create([
            'title' => 'Lorem ipsum for testing  the search function',
            'description' => 'Dolor sit amet for testing the search function',
            'owner_id' => User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']),
        ]);

        Asset::factory()->create(['clip_id' => $this->clip]);

        $this->mockHandler = $this->swapElasticsearchGuzzleClient();
        $this->elasticsearchService = app(ElasticsearchService::class);
    }

    protected function searchFor($term): TestResponse
    {
        return $this::get(route('search').'?term='.$term);
    }

    /** @test */
    public function it_uses_elasticsearch_if_it_is_available(): void
    {
        $this->mockHandler->append(
            $this->mockClusterHealthResponse()
        );

        $this->startStream($this->clip);
        $this->mockSingleDocument();

        $response = $this->searchFor('lorem');

        $response->assertOk()->assertSee($this->clip->id);

        $this->closeStream();
    }

    /** @test */
    public function it_shows_an_error_when_search_is_empty(): void
    {
        $this->searchFor('')->assertSessionHasErrors('term');
    }

    /** @test */
    public function it_shows_an_error_when_search_term_is_less_than_3_chars(): void
    {
        $this->searchFor('ab')->assertSessionHasErrors('term');
    }

    /** @test */
    public function it_renders_a_results_page(): void
    {
        //disable elasticsearch
        $this->mockHandler->append($this->mockClusterNotAvailable());

        $response = $this->searchFor('test');
        $response->assertOk()->assertViewHas('searchResults');
    }

    /** @test */
    public function it_returns_only_clips_with_assets(): void
    {
        //disable elasticsearch
        $this->mockHandler->append($this->mockClusterNotAvailable());

        $clip = Clip::factory()->create(['title' => 'without assets', 'description' => 'clip without assets']);

        $this->searchFor('assets')->assertSee(__('search.no results found'));
    }

    /** @test */
    public function it_searches_for_clip_title(): void
    {
        //disable elasticsearch
        $this->mockHandler->append($this->mockClusterNotAvailable());

        $this->searchFor('lorem')->assertSee(Str::limit($this->clip->title, 20, '...'));
    }

    /** @test */
    public function it_searches_for_clip_description(): void
    {
        //disable elasticsearch
        $this->mockHandler->append($this->mockClusterNotAvailable());

        $this->searchFor('dolor')->assertSee(Str::limit($this->clip->title, 20, '...'));
    }

    /** @test */
    public function it_searches_for_clip_owner(): void
    {
        //disable elasticsearch
        $this->mockHandler->append($this->mockClusterNotAvailable());

        $this->searchFor('Doe')->assertSee($this->clip->owner->first_name);
    }

    /** @test */
    public function it_searches_for_multiple_owners(): void
    {
        //disable elasticsearch
        $this->mockHandler->append($this->mockClusterNotAvailable());

        $secondClip = Clip::factory()->create([
            'title' => 'Lorem ipsum for testing  the search function',
            'description' => 'Dolor sit amet for testing the search function',
            'owner_id' => User::factory()->create(['first_name' => 'Bob', 'last_name' => 'Doe']),
        ]);

        Asset::factory()->create(['clip_id' => $secondClip]);

        $this->searchFor('doe')
            ->assertSee(Str::limit($this->clip->title, 20, '...'))
            ->assertSee(Str::limit($secondClip->title, 20, '...'));
    }
}
