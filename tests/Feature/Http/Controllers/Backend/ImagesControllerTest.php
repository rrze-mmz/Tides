<?php

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

uses()->group('backend');

it('denies access to images index page for guests', function () {
    get(route('images.index'))->assertRedirectToRoute('login');
});

it('denies access to images index page for logged in users', function () {
    actingAs(signInRole('user'));

    get(route('images.index'))->assertForbidden();
});

it('allows access to images index page for moderators', function () {
    actingAs(signInRole('moderator'));

    get(route('images.index'))->assertOk();
});

it('it allows access to images index for assistants, admins and superadmins', function () {
    actingAs(signInRole('assistant'));
    get(route('images.index'))->assertOk();
    auth()->logout();

    actingAs(signInRole('admin'));
    get(route('images.index'))->assertOk();
    auth()->logout();

    actingAs(signInRole('superadmin'));
    get(route('images.index'))->assertOk();
    auth()->logout();
});

it('has a button to create new images and a text for info if database has no images', function () {
    actingAs(signInRole('admin'));

    get(route('images.index'))->assertSee('No images found. Please create one');
});

it('shows a list of all images', function () {
    actingAs(signInRole('moderator'));

    get(route('images.index'))->assertSee(Image::all()->first()->file_name);
})->with([fn () => Image::factory(10)->create()]);
