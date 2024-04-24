<?php

use App\Enums\Role;
use App\Models\Setting;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses()->group('backend');

beforeEach(function () {
    signInRole(Role::SUPERADMIN);
    $this->setting = Setting::opencast();
});

it('denies opencast settings page in roles other than superadmin', function () {
    auth()->logout();
    signInRole(Role::MODERATOR);
    $this->get(route('settings.opencast.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    get(route('settings.opencast.show'))->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    get(route('settings.opencast.show'))->assertForbidden();
});

it('shows opencast settings page', function () {
    get(route('settings.opencast.show'))
        ->assertOk()
        ->assertViewIs('backend.settings.workflow')
        ->assertSee('localhost:8080')
        ->assertSee('admin')
        ->assertSee('Admin password');
});

it('requires an opencast admin url for opencast settings page', function () {
    $attributes = [
        'username' => 'admin',
        'password' => '1234',
    ];

    put(route('settings.opencast.update', $this->setting), $attributes)
        ->assertSessionHasErrors('url');
});

it('requires an opencast admin username for opencast settings page', function () {
    $attributes = [
        'url' => 'test.com',
        'password' => '1234',
    ];

    put(route('settings.opencast.update', $this->setting), $attributes)
        ->assertSessionHasErrors('username');
});

it('requires an opencast admin password for opencast settings page', function () {
    $attributes = [
        'url' => 'test.com',
        'username' => 'admin',
    ];

    put(route('settings.opencast.update', $this->setting), $attributes)
        ->assertSessionHasErrors('password');
});

it('denies updating opencast settings in roles other than superadmin', function () {
    auth()->logout();
    $attributes = [
        'url' => 'test.com',
        'username' => 'test',
        'password' => 1234,
        'archive_path' => '/archive/mh_default',
        'default_workflow_id' => 'fast-test',
        'upload_workflow_id' => 'fast-test',
        'theme_id_top_right' => '500',
        'theme_id_top_left' => '501',
        'theme_id_bottom_left' => '502',
        'theme_id_bottom_right' => '503',
    ];
    signInRole(Role::MODERATOR);
    put(route('settings.opencast.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ASSISTANT);
    put(route('settings.opencast.update', $this->setting), $attributes)->assertForbidden();

    auth()->logout();
    signInRole(Role::ADMIN);
    put(route('settings.opencast.update', $this->setting), $attributes)->assertForbidden();

    expect(Setting::opencast()->data['url'])->toBe('localhost:8080');
});

it('updates opencast settings page', function () {
    $attributes = [
        'url' => 'http://test.com',
        'username' => 'test',
        'password' => 1234,
        'archive_path' => '/archive/mh_default',
        'default_workflow_id' => 'fast-test',
        'upload_workflow_id' => 'fast-test',
        'theme_id_top_right' => '500',
        'theme_id_top_left' => '501',
        'theme_id_bottom_left' => '502',
        'theme_id_bottom_right' => '503',
        'assistant_group_name' => 'ROLE_GROUP_TIDES_ASSISTANTS2',
    ];
    put(route('settings.opencast.update', $this->setting), $attributes);

    $this->setting->refresh();
    expect($this->setting->opencast()->data['url'])->toBe($attributes['url']);
    assertDatabaseHas('settings', ['data' => json_encode($attributes)]);
});
