<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GenerateOrganizationSlugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_organization_slug(): void
    {
        $organization = Organization::find(1);

        $organization->slug = null;
        $organization->save();
        $organization->refresh();

        $this->artisan('organizations:slugs')->expectsOutput('Finish organizations slugs');

        $organization->refresh();

        $this->assertEquals($organization->slug, Str::slug($organization->name));
    }
}
