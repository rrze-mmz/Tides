<?php

namespace Tests\Unit;

use App\Models\Presenter;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PresenterTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_belongs_to_many_clips(): void
    {
        $presenter = Presenter::factory()->create();

        $this->assertInstanceOf(BelongsToMany::class, $presenter->clips());

    }
}
