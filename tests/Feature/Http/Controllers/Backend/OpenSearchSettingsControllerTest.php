<?php

use App\Enums\Role;
use App\Models\Setting;

use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses()->group('backend');

beforeEach(function () {
    signInRole(Role::SUPERADMIN);
    $this->setting = Setting::openSearch();
});

it('allows viewing openSearch settings page only to superadmins', function () {
    auth()->logout();
    signInRole(Role::MODERATOR);
    get(route('settings.openSearch.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    get(route('settings.openSearch.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    get(route('settings.openSearch.show'))->assertForbidden();
});

it('displays OpenSearch settings page', function () {
    get(route('settings.openSearch.show'))
        ->assertOk()
        ->assertViewIs('backend.settings.openSearch')
        ->assertSee('URL')
        ->assertSee('port')
        ->assertSee('username')
        ->assertSee('password');
});

it('requires a server url and port for OpenSearch settings page', function () {
    $attributes = [
        'username' => 'admin',
        'password' => '1234',
    ];

    put(route('settings.openSearch.update', $this->setting), $attributes)
        ->assertSessionHasErrors(['url', 'port']);
});

it('denies updating OpenSearch settings page on user roles other than superadmin', function () {
    auth()->logout();
    $attributes = [
        'url' => 'http://test.com',
        'port' => 9200,
        'username' => 'admin',
        'password' => 'admin',
    ];
    signInRole(Role::MODERATOR);
    put(route('settings.openSearch.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    put(route('settings.openSearch.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    put(route('settings.openSearch.update', $this->setting), $attributes)->assertForbidden();

    expect(Setting::openSearch()->data['url'])->toBe('localhost');
});

it('updates OpenSearch settings page', function () {
    $attributes = [
        'url' => 'http://test.com',
        'port' => 9200,
        'username' => 'admin',
        'password' => 'admin',
        'prefix' => 'tides',
    ];

    put(route('settings.openSearch.update', $this->setting), $attributes)->assertRedirect();

    $this->setting->refresh();

    expect($this->setting->openSearch()->data['url'])->toBe($attributes['url']);
});
