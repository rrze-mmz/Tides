<?php

namespace Tests\Unit;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_clips(): void
    {
        $tag = Tag::factory()->create();

        $this->assertInstanceOf(BelongsToMany::class, $tag->clips());
    }

}
