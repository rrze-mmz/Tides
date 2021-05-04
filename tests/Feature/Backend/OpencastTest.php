<?php

namespace Tests\Feature\Backend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpencastTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_should_check_for_opencast_status(): void
    {
        $this->signIn();

        $this->get(route('opencast'))->assertStatus(200);
    }


}
