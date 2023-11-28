<?php

use App\Livewire\ClipsDataTable;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ClipsDataTable::class)
        ->assertStatus(200);
});
