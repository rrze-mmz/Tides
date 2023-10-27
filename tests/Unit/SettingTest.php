<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

uses()->group('unit');

it('has an opencast scope', function () {
    expect(Setting::opencast())->toBeInstanceOf(Setting::class);
});

it('has a portal scope', function () {
    expect(Setting::portal())->toBeInstanceOf(Setting::class);
});

it('has a streaming scope', function () {
    expect(Setting::streaming())->toBeInstanceOf(Setting::class);
});

it('has a user scope', function () {
    expect(Setting::user(User::factory()->create()))->toBeInstanceOf(Builder::class);
});

it('has an Opensearch scope', function () {
    expect(Setting::openSearch())->toBeInstanceOf(Setting::class);
});
