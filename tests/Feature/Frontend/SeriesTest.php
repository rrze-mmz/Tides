<?php

namespace Tests\Feature\Frontend;

use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_visitor_cannot_manage_series(): void
    {
        $this->post(route('series.store'),[])->assertRedirect('login');
    }
}
