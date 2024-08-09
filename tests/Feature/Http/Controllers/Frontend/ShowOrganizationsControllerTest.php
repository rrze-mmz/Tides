<?php

use App\Models\Organization;
use App\Models\Series;

use function Pest\Laravel\get;

uses()->group('frontend');

it('has a faculties index page', function () {
    get(route('frontend.organizations.index'))->assertOk();

    get(route('frontend.organizations.index'))->assertSee(__('organization.index.Organization index'));
});

it('shows all public series for a organization', function () {
    $organization = Organization::find(1);

    $series = Series::factory()->create([
        'organization_id' => 1,
        'is_public' => false,
    ]);

    get(route('frontend.organizations.show', $organization))->assertDontSee($series->title);
});
