<?php

use App\Models\Organization;
use Illuminate\Support\Str;

it('generates organization slug', function () {
    $organization = Organization::find(1);

    $organization->slug = null;
    $organization->save();
    $organization->refresh();

    $this->artisan('organizations:slugs')->expectsOutput('Finish organizations slugs');

    $organization->refresh();

    expect(Str::slug($organization->name))->toEqual($organization->slug);
});
