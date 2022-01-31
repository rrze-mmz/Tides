<?php

namespace Tests\Unit;

use App\Models\Presenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresenterTest extends TestCase
{
    use RefreshDatabase;

    private Presenter $presenter;

    public function setUp(): void
    {
        parent::setUp();

        $this->presenter = Presenter::factory()->create();
    }

    /** @test */
    public function it_belongs_to_many_series(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->presenter->series());
    }

    /** @test */
    public function it_belongs_to_many_clips(): void
    {
        $this->assertInstanceOf(BelongsToMany::class, $this->presenter->clips());
    }

    /** @test */
    public function it_has_one_or_none_degree_title(): void
    {
        $this->assertInstanceOf(BelongsTo::class, $this->presenter->academic_degree());
    }

    /** @test */
    public function it_can_return_presenter_full_name(): void
    {
        $this->assertEquals($this->presenter->getFullNameAttribute(),
            $this->presenter->academic_degree->title . ' ' . $this->presenter->first_name .
            ' ' . $this->presenter->last_name);
    }
}
