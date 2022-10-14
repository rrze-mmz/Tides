<?php

namespace Tests\Unit;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SemesterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_clips(): void
    {
        $this->assertInstanceOf(HasMany::class, Semester::find(1)->clips());
    }

    /** @test */
    public function it_has_a_current_semester_scope(): void
    {
        $this->assertInstanceOf(Builder::class, Semester::current());
    }
}
