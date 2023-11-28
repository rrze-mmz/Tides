<?php

use App\Enums\Role;

uses()->group('backend');

use App\Livewire\SearchDataTable;

use function Pest\Laravel\get;

beforeEach(function () {
    signInRole(Role::MODERATOR);
});

it('denies showing admin search results for members or users', function () {
    auth()->logout();
    signInRole(Role::MEMBER);
    get(route('admin.search'))->assertForbidden();

    auth()->logout();
    signInRole(Role::STUDENT);
    get(route('admin.search'))->assertForbidden();
});

it('has a url for showing backend search results', function () {
    get(route('admin.search'))->assertOk();
});

it('loads a livewire component for showing admin search results', function () {
    get(route('admin.search'))->assertSeeLivewire(SearchDataTable::class);
});
