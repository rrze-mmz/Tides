<?php

use App\Enums\Role;
use App\Models\Clip;
use App\Models\Series;

use function Pest\Laravel\get;

uses()->group('backend');

beforeEach(function () {
    $this->series = Series::factory()->create();
    $this->clip = Clip::factory()->create();
});
test('statistics page for series redirects visitors to login page', function () {
    get(route('statistics.series', $this->series))->assertRedirectToRoute('login');
});

test('statistics page for clip redirects visitors to login page', function () {
    get(route('statistics.clip', $this->clip))->assertRedirectToRoute('login');
});

it('denies access to statistics page for students or moderators that does not belong to series acls', function () {
    signInRole(Role::STUDENT);

    get(route('statistics.series', $this->series))->assertForbidden();
    get(route('statistics.clip', $this->clip))->assertForbidden();

    auth()->logout();
    signInRole(Role::MODERATOR);

    get(route('statistics.series', $this->series))->assertForbidden();
    get(route('statistics.clip', $this->clip))->assertForbidden();
});

it('allows to series members or portal administrators to series statistics', function () {
    $this->series->addMember(signInRole(Role::MODERATOR));
    get(route('statistics.series', $this->series))->assertOk();
    auth()->logout();

    signInRole(Role::ASSISTANT);
    get(route('statistics.series', $this->series))->assertOk();
    auth()->logout();

    signInRole(Role::ADMIN);
    get(route('statistics.series', $this->series))->assertOk();
});

it('allows to series members or portal administrators to clip statistics', function () {
    $this->clip->owner_id = signInRole(Role::MODERATOR)->id;
    $this->clip->save();

    get(route('statistics.clip', $this->clip))->assertOk();
    auth()->logout();

    signInRole(Role::ASSISTANT);
    get(route('statistics.clip', $this->clip))->assertOk();
    auth()->logout();

    signInRole(Role::ADMIN);
    get(route('statistics.clip', $this->clip))->assertOk();
});

it('has a statistics backend index view', function () {
    signInRole(Role::ADMIN);
    get(route('statistics.series', $this->series))
        ->assertViewHas(['statistics', 'obj'])
        ->assertViewIs('backend.statistics.index');
});
