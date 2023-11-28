<?php

use App\Enums\Role;
use App\Livewire\SearchDataTable;

use function Pest\Laravel\get;

uses()->group('backend');

it('renders a livewire data table component for search results page for admin portal', function () {
    signInRole(Role::MODERATOR);

    get(route('admin.search', ['term' => 'test']))->assertSeeLivewire(SearchDataTable::class);
});
