<?php

use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use App\Services\ElasticsearchService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\Setup\WorksWithElasticsearchClient;

use function Pest\Laravel\get;

uses(WithFaker::class);

uses(WorksWithElasticsearchClient::class);

uses()->group('frontend');

/*
/   Set up the test
/   Create a clip with an asset
*/
beforeEach(function () {
    $this->clip = Clip::factory()->create([
        'title' => 'Lorem ipsum for testing  the search function',
        'description' => 'Dolor sit amet for testing the search function',
        'owner_id' => User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']),
    ]);

    Asset::factory()->create(['clip_id' => $this->clip]);

    $this->mockHandler = $this->swapElasticsearchGuzzleClient();
    $this->elasticsearchService = app(ElasticsearchService::class);
});

function searchFor($term): TestResponse
{
    return get(route('search').'?term='.$term);
}

it('uses elasticsearch if it is available', function () {
    $this->mockHandler->append(
        $this->mockClusterHealthResponse()
    );
    $this->startStream($this->clip);
    $this->mockSingleDocument();
    $response = searchFor('lorem');

    $response->assertOk()->assertSee($this->clip->id);
    $this->closeStream();
});

it('shows an error when search is empty', function () {
    searchFor('')->assertSessionHasErrors('term');
});

it('shows an error when search term is less than 3 chars', function () {
    searchFor('ab')->assertSessionHasErrors('term');
});

it('renders a results page', function () {
    //disable elasticsearch
    $this->mockHandler->append($this->mockClusterNotAvailable());
    $response = searchFor('test');

    $response->assertOk()->assertViewHas('searchResults');
});

it('returns only clips with assets', function () {
    //disable elasticsearch
    $this->mockHandler->append($this->mockClusterNotAvailable());
    Clip::factory()->create(['title' => 'without assets', 'description' => 'clip without assets']);

    searchFor('assets')->assertSee(__('search.no results found'));
});

it('searches for clip title', function () {
    //disable elasticsearch
    $this->mockHandler->append($this->mockClusterNotAvailable());

    searchFor('lorem')->assertSee($this->clip->title);
});

it('searches for clip description', function () {
    //disable elasticsearch
    $this->mockHandler->append($this->mockClusterNotAvailable());

    searchFor('dolor')->assertSee($this->clip->title);
});

it('searches for clip owner', function () {
    //disable elasticsearch
    $this->mockHandler->append($this->mockClusterNotAvailable());

    searchFor('Doe')->assertSee($this->clip->owner->first_name);
});

it('searches for multiple owners', function () {
    //disable elasticsearch
    $this->mockHandler->append($this->mockClusterNotAvailable());
    $secondClip = Clip::factory()->create([
        'title' => 'Lorem ipsum for testing  the search function',
        'description' => 'Dolor sit amet for testing the search function',
        'owner_id' => User::factory()->create(['first_name' => 'Bob', 'last_name' => 'Doe']),
    ]);
    Asset::factory()->create(['clip_id' => $secondClip]);

    searchFor('doe')
        ->assertSee($this->clip->title)
        ->assertSee($secondClip->title);
});
