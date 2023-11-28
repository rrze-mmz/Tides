<?php

use App\Enums\Role;
use App\Livewire\ClipsDataTable;

use function Pest\Laravel\get;

uses()->group('backend');

it('renders a livewire data table component for clips index page', function () {
    signInRole(Role::MODERATOR);

    get(route('clips.index'))->assertSeeLivewire(ClipsDataTable::class);
});
