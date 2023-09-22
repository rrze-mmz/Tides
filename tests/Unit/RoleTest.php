<?php

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

uses()->group('unit');

it('has many users', function () {
    $role = Role::where('name', 'admin')->first();

    expect($role->users())->toBeInstanceOf(BelongsToMany::class);
});
