<?php

namespace Tests\Feature\Backend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageSeriesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_authenticated_user_can_see_the_create_series_form_and_all_form_fields()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $this->get(route('series.create'))->assertSee('title')
            ->assertSee('description');
    }

}
