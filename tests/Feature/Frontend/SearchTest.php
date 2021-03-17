<?php

namespace Tests\Feature\Frontend;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

       $this->clip =  Clip::factory()->create([
            'title' => 'Lorem ipsum for testing  the search function',
            'description' => 'Dolor sit amet for testing the search function',
            'owner_id' => User::factory()->create(['name' => 'John Doe'])
        ]);

       Asset::factory()->create(['clip_id' => $this->clip]);
    }

    /** @test */
    public function search_term_should_not_be_empty()
    {
        $this->post('/search', ['searchTerm' => ''])
            ->assertSessionHasErrors('searchTerm');
    }

    /** @test */
    public function search_term_should_not_be_less_than_3_chars()
    {
        $this->post('/search', ['searchTerm' => 'ab'])
            ->assertSessionHasErrors('searchTerm');
    }

    /** @test */
    public function search_should_return_only_clips_with_assets()
    {
        $clipWithoutAssets = Clip::factory()->create(['title' => 'Clip without video']);

        $this->followingRedirects()
            ->post('/search', ['searchTerm' => 'video'])
            ->assertDontSee($clipWithoutAssets->title);
    }
    /** @test */
    public function a_user_can_search_for_clip_title()
    {
        $this->followingRedirects()
            ->post('/search', ['searchTerm' => 'lorem'])
            ->assertSee($this->clip->title);
    }

    /** @test */
    public function a_user_can_search_for_clip_description()
    {
        $this->followingRedirects()
            ->post('/search', ['searchTerm' => 'Doe'])
            ->assertSee($this->clip->owner->name);
    }
}
