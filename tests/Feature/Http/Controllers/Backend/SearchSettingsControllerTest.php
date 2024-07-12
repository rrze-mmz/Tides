<?php

use App\Enums\Role;
use App\Models\Clip;
use App\Models\Image;
use App\Models\Podcast;
use App\Models\Presenter;
use App\Models\Series;
use App\Models\Setting;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses()->group('backend');

beforeEach(function () {
    signInRole(Role::SUPERADMIN);
    $this->setting = Setting::openSearch();
});

function searchBackendFor($term)
{
    return get(route('admin.search', ['term' => $term]));
}

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
        ->assertViewIs('backend.settings.search')
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

it('can search in the backend for series with certain id using the pattern s:', function () {
    $series = Series::factory()->create(['id' => 100]);
    searchBackendFor('s:100')->assertRedirectToRoute('series.edit', $series);
});

it('can search in the backend for clip with certain id using the pattern c:', function () {
    $clip = Clip::factory()->create(['id' => 100]);
    searchBackendFor('c:100')->assertRedirectToRoute('clips.edit', $clip);
});

it('can search in the backend for presenter with certain id using the pattern p:', function () {
    $presenter = Presenter::factory()->create(['id' => 100]);
    searchBackendFor('p:100')->assertRedirectToRoute('presenters.edit', $presenter);
});

it('can search in the backend for image with certain id using the pattern i:', function () {
    $image = Image::factory()->create(['id' => 100]);
    searchBackendFor('i:100')->assertRedirectToRoute('images.edit', $image);
});

it('can search in the backend for user with certain id using the pattern u:', function () {
    $user = User::factory()->create(['id' => 100]);
    searchBackendFor('u:100')->assertRedirectToRoute('users.edit', $user);
});

it('can search in the backend for podcasts with certain id using the pattern pd:', function () {
    $podcast = Podcast::factory()->create(['id' => 100]);
    searchBackendFor('pd:100')->assertRedirectToRoute('podcasts.edit', $podcast);
});
