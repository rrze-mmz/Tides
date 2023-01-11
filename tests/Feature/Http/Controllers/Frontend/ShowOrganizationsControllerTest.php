<?php

namespace Tests\Feature\Http\Controllers\Frontend;

use App\Models\Organization;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowOrganizationsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_faculties_index_page(): void
    {
        $this->get(route('frontend.organizations.index'))->assertOk();

        $this->get(route('frontend.organizations.index'))->assertSee('Organizations index');
    }

    /** @test */
    public function it_shows_all_public_series_for_a_organization(): void
    {
        $organization = Organization::find(1);

        $series = Series::factory()->create([
            'organization_id' => 1,
            'is_public' => false,
        ]);

        $this->get(route('frontend.organizations.show', $organization))->assertDontSee($series->title);
    }
}
