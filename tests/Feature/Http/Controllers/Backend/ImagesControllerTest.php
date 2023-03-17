<?php

use App\Models\Image;
use function Pest\Laravel\get;

uses()->group('backend');

it('denies access to images index page for guests', function () {
    get(route('images.index'))->assertRedirectToRoute('login');
});

it('denies access to images index page for logged in users', function () {
    signInRole('user');

    get(route('images.index'))->assertForbidden();
});

it('allows access to images index page for moderators', function () {
    signInRole('moderator');

    get(route('images.index'))->assertOk();
});

it('it allows access to images index for assistants, admins and superadmins', function () {
    signInRole('assistant');
    get(route('images.index'))->assertOk();
    auth()->logout();

    signInRole('admin');
    get(route('images.index'))->assertOk();
    auth()->logout();

    signInRole('superadmin');
    get(route('images.index'))->assertOk();
    auth()->logout();
});

it('has a button to create new images and a text for info if database has no images', function () {
    signInRole('admin');

    get(route('images.index'))
        ->assertViewIs('backend.images.index')
        ->assertViewHas('images')
        ->assertSee('No images found. Please create one');
});

it('shows a list of all images', function () {
    signInRole('moderator');

    get(route('images.index'))
        ->assertSee(Image::all()->first()->file_name);
})->with([fn () => Image::factory(10)->create()]);

it('has edit and delete links for every image', function () {
    signInRole('admin');

    get(route('images.index'))
        ->assertSee(route('images.edit', Image::all()->first()))
        ->assertSee(route('images.destroy', Image::all()->first()));
})->with([fn () => Image::factory(10)->create()]);

it('denies access to image create form for portal users', function () {
    signInRole('user');

    get(route('images.create'))->assertForbidden();
});

it('denies access to image create form for portal moderators', function () {
    signInRole('moderator');

    get(route('images.create'))->assertForbidden();
});

it('paginates the results', function () {
    signInRole('moderator');

    get(route('images.index'))->assertDontSee(route('images.edit', Image::all()->last()));
})->with([fn () => Image::factory(50)->create()]);

it('denies access to image create form for portal assistants', function () {
    signInRole('assistant');

    get(route('images.create'))->assertForbidden();
});

it('allows access to image create for a minimum role of portal admin', function () {
    signInRole('admin');

    get(route('images.create'))
        ->assertOk()
        ->assertViewIs('backend.images.create');
});

test('create new image should render a form with all necessary fields', function () {
    signInRole('admin');

    get(route('images.create'))->assertSee('file_name');
})->skip('TODO');
