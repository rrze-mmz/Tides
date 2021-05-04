<?php


namespace Tests\Feature\Frontend;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SearchTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected $clip;

    /*
    /   Set up the test
    /   Create a clip with an asset
    */
    protected function setUp(): void
    {
        parent::setUp();

        $this->clip = Clip::factory()->create([
            'title'       => 'Lorem ipsum for testing  the search function',
            'description' => 'Dolor sit amet for testing the search function',
            'owner_id'    => User::factory()->create(['name' => 'John Doe'])
        ]);

        Asset::factory()->create(['clip_id' => $this->clip]);
    }

    protected function searchFor($term): TestResponse
    {
        return $this::get(route('search').'?term='.$term);
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
    public function it_returns_only_clips_with_assets(): void
    {
        Clip::factory()->create(['title' => 'Clip without video']);

        $this->searchFor('video')->assertSee('No results found');
    }

    /** @test */
    public function it_searches_for_clip_title(): void
    {
        $this->searchFor('lorem')->assertSee($this->clip->title);
    }

    /** @test */
    public function it_searches_for_clip_description(): void
    {
        $this->searchFor('dolor')->assertSee($this->clip->title);
    }

    /** @test */
    public function it_searches_for_clip_owner(): void
    {
        $this->searchFor('Doe')->assertSee($this->clip->owner->name);
    }

    /** @test */
    public function it_searches_for_multiple_owners(): void
    {
        $secondClip = Clip::factory()->create([
            'title'       => 'Lorem ipsum for testing  the search function',
            'description' => 'Dolor sit amet for testing the search function',
            'owner_id'    => User::factory()->create(['name' => 'Bob Doe'])
        ]);

        Asset::factory()->create(['clip_id' => $secondClip]);

        $this->searchFor('doe')
            ->assertSee($this->clip->title)
            ->assertSee($secondClip->title);
    }
}
