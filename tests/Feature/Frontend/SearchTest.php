<?php

namespace Tests\Feature\Frontend;

use App\Models\Clip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_search_for_clips()
    {
        Clip::factory()->create(['title'=> 'Tides videoportal']);

        $this->followingRedirects()
            ->post('/search', ['searchTerm' => 'Tides'])
            ->assertSee('Tides videoportal');
    }
}
