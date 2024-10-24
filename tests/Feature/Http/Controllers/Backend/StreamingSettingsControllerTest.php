<?php

use App\Enums\Role;
use App\Models\Setting;

use function Pest\Laravel\get;
use function Pest\Laravel\put;
use function Pest\Laravel\withoutExceptionHandling;

uses()->group('backend');

beforeEach(function () {
    // TODO: Change the autogenerated stub
    signInRole(Role::SUPERADMIN);
    $this->setting = Setting::streaming();
});

it('denies streaming settings page in roles other than superadmin', function () {
    auth()->logout();
    signInRole(Role::MODERATOR);
    get(route('settings.streaming.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    get(route('settings.streaming.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    get(route('settings.streaming.show'))->assertForbidden();
});

it('shows streaming settings page', function () {
    withoutExceptionHandling();
    get(route('settings.streaming.show'))
        ->assertOk()
        ->assertViewIs('backend.settings.streaming')
        ->assertViewHas(['setting' => $this->setting->data])
        ->assertSee('localhost:1935')
        ->assertSee('localhost:8087')
        ->assertSee('Digest username')
        ->assertSee('Digest password');
});

it('requires an streaming engine url for streaming settings page', function () {
    $attributes = [
        'wowza_server1_api_url' => 'http://localost:8087',
        'wowza_server1_api_username' => 'admin',
        'wowza_server1_api_password' => '1234',
    ];

    put(route('settings.streaming.update', $this->setting), $attributes)
        ->assertSessionHasErrors('wowza_server1_engine_url');
});

it('requires an streaming api url for streaming settings page', function () {
    $attributes = [
        'wowza_server1_engine_url' => 'http://localost:1935',
        'wowza_server1_api_username' => 'admin',
        'wowza_server1_api_password' => '1234',
    ];

    put(route('settings.streaming.update', $this->setting), $attributes)
        ->assertSessionHasErrors('wowza_server1_api_url');
});

it('denies updating streaming settings in roles other than superadmin', function () {
    auth()->logout();
    $attributes = [
        'wowza_server1_engine_url' => 'test.com',
        'wowza_server1_api_url' => 'test.com',
        'wowza_server1_api_username' => 'test',
        'wowza_server1_api_password' => 1234,
    ];
    signInRole(Role::MODERATOR);

    put(route('settings.streaming.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();

    signInRole(Role::ASSISTANT);

    put(route('settings.streaming.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();

    signInRole(Role::ADMIN);

    put(route('settings.streaming.update', $this->setting), $attributes)->assertForbidden();
});

it('updates streaming settings page', function () {
    $attributes = config('settings.streaming');
    put(route('settings.streaming.update', $this->setting), $attributes);

    expect($this->setting->streaming()->data['wowza']['server1']['engine_url'])
        ->toEqual($attributes['wowza']['server1']['engine_url']);
});

afterEach(function () {
    // TODO: Change the autogenerated stub
});
