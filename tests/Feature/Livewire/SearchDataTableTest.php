<?php

use App\Livewire\SearchDataTable;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(SearchDataTable::class)
        ->assertStatus(200);
});
