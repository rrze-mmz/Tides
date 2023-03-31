<?php

use App\Models\Clip;
use App\Models\Organization;
use App\Models\Presenter;
use App\Models\Tag;
use function Pest\Laravel\get;

uses()->group('frontend');

it('search for clips', function () {
    $testClip = Clip::factory()->create(['title' => 'test clip']);
    $tidesClip = Clip::factory()->create(['title' => 'tides clip']);

    get(route('api.clips').'?query=test')->assertOk()->assertJson([
        ['id' => 1, 'name' => $testClip->title],
    ]);

    get(route('api.clips').'?query=clip')->assertOk()->assertJson([
        ['id' => 1, 'name' => $testClip->title],
        ['id' => 2, 'name' => $tidesClip->title],
    ]);
});

it('search for tags', function () {
    Tag::factory()->create(['name' => 'algebra']);

    get(route('api.tags').'?query=algebra')->assertOk()->assertJson([
        ['id' => 1, 'name' => 'algebra'],
    ]);
});

it('search presenters', function () {
    Presenter::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

    get(route('api.presenters').'?query=john')
        ->assertOk()
        ->assertJson([
            ['id' => 1, 'name' => 'Dr. John Doe'],
        ]);
});

it('it is not allowed for a guest to use the user api', function () {
    get(route('api.users').'?query=john')->assertForbidden();
});

it('can search organizations', function (Organization $organization) {
    get(route('api.organizations').'?query=test')->assertOk()->assertJson([
        ['id' => 2, 'name' => 'This is a test'],
    ]);
})->with([
    fn () => Organization::factory()->create([
        'org_id' => 2,
        'name' => 'This is a test',
        'parent_org_id' => 2,
        'orgno' => '0000000001',
        'shortname' => 'Main organization unit',
        'staff' => null,
        'startdate' => now(),
        'operationstartdate' => now(),
        'operationenddate' => '2999-12-31',
        'created_at' => now(),
        'updated_at' => null,
    ]),
]);
