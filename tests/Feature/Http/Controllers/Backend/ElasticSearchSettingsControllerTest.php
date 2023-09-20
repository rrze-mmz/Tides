<?php

use App\Enums\Role;
use App\Models\Setting;

use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses()->group('backend');

beforeEach(function () {
    signInRole(Role::SUPERADMIN);
    $this->setting = Setting::elasticSearch();
});

it('allows viewing elasticSearch settings page only to superadmin', function () {
    auth()->logout();
    signInRole(Role::MODERATOR);
    get(route('settings.elasticSearch.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    get(route('settings.elasticSearch.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    get(route('settings.elasticSearch.show'))->assertForbidden();
});

it('displays elastisearch settings page', function () {
    get(route('settings.elasticSearch.show'))
        ->assertOk()
        ->assertViewIs('backend.settings.elasticSearch')
        ->assertSee('URL')
        ->assertSee('port')
        ->assertSee('username')
        ->assertSee('password');
});

it('requires a server url and port for elasticsearch settings page', function () {
    $attributes = [
        'username' => 'admin',
        'password' => '1234',
    ];

    put(route('settings.elasticSearch.update', $this->setting), $attributes)
        ->assertSessionHasErrors(['url', 'port']);
});

it('allows updating elasticSearch settings page only to superadmin ', function () {
    auth()->logout();
    $attributes = [
        'url' => 'http://test.com',
        'port' => 9200,
        'username' => 'elastic',
        'password' => 'changeme',
    ];
    signInRole(Role::MODERATOR);
    put(route('settings.elasticSearch.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    put(route('settings.elasticSearch.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    put(route('settings.elasticSearch.update', $this->setting), $attributes)->assertForbidden();

    expect(Setting::elasticSearch()->data['url'])->toBe('localhost');
});

it('updates elasticsearch settings page', function () {
    $attributes = [
        'url' => 'http://test.com',
        'port' => 9200,
        'username' => 'elastic',
        'password' => 'password',
        'prefix' => 'tides',
    ];

    put(route('settings.elasticSearch.update', $this->setting), $attributes)->assertRedirect();

    $this->setting->refresh();

    expect($this->setting->elasticSearch()->data['url'])->toBe($attributes['url']);
});
