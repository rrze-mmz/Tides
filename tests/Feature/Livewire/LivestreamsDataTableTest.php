<?php

use App\Livewire\LivestreamsDataTable;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(LivestreamsDataTable::class)
        ->assertStatus(200);
});
