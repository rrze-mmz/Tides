<?php

use App\Enums\Role;
use App\Models\Setting;

use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses()->group('backend');

beforeEach(function () {
    signInRole(Role::SUPERADMIN);
    $this->setting = Setting::portal();
});

it('has portal settings page ', function () {
    get(route('settings.portal.show'))
        ->assertOk()
        ->assertSee('Maintenance mode');
});

it('can set portal to maintenance mode via portal settings', function () {
    $attributes = [
        'maintenance_mode' => 'on',
    ];

    put(route('settings.portal.update', $this->setting), $attributes)
        ->assertSessionDoesntHaveErrors('maintenance_mode');

    auth()->logout();
});

it('requires a feeds default owner name field', function () {
    $attributes = [];

    put(route('settings.portal.update', $this->setting), $attributes)
        ->assertSessionHasErrors('feeds_default_owner_name');
});

test('a feeds default owner name field must be a string', function () {
    $attributes = [
        'feeds_default_owner_name' => true,
    ];
    put(route('settings.portal.update', $this->setting), $attributes)
        ->assertSessionHasErrors('feeds_default_owner_name');

    $attributes = [
        'feeds_default_owner_name' => 'test',
    ];
    put(route('settings.portal.update', $this->setting), $attributes)
        ->assertSessionDoesntHaveErrors('feeds_default_owner_name');
});

it('requires a feeds default owner email field', function () {
    $attributes = [];

    put(route('settings.portal.update', $this->setting), $attributes)
        ->assertSessionHasErrors('feeds_default_owner_email');
});

test('a feeds default owner email field must be an email', function () {
    $attributes = [
        'feeds_default_owner_email' => 'test',
    ];
    put(route('settings.portal.update', $this->setting), $attributes)
        ->assertSessionHasErrors('feeds_default_owner_email');

    $attributes = [
        'feeds_default_owner_email' => 'test@test.com',
    ];
    put(route('settings.portal.update', $this->setting), $attributes)
        ->assertSessionDoesntHaveErrors('feeds_default_owner_email');
});
