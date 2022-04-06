<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchableTraitTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_search_for_users(): void
    {
        User::factory()->create(['first_name' => 'Alice']);
        User::factory()->create(['first_name' => 'Bob']);
        User::factory()->create(['first_name' => 'Max']);

        $this->assertInstanceOf(Collection::class, User::search('bob')->get());

        $this->assertEquals(1, User::search('bob')->count());
    }
}
