<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);
uses()->group('unit');

it('can search for users', function () {
    User::factory()->create(['first_name' => 'Alice']);
    User::factory()->create(['first_name' => 'Bob']);
    User::factory()->create(['first_name' => 'Max']);

    expect(User::search('bob')->get())->toBeInstanceOf(Collection::class);
    expect(User::search('bob')->count())->toEqual(1);
});
