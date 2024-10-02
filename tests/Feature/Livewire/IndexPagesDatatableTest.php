<?php

use App\Livewire\IndexPagesDatatable;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(IndexPagesDatatable::class)
        ->assertStatus(200);
});
