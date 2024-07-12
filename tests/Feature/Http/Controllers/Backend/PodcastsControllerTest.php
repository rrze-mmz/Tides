<?php

use App\Enums\Role;
use App\Models\Podcast;
use Facades\Tests\Setup\PodcastFactory;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses()->group('backend');

beforeEach(function () {
    $this->podcast = PodcastFactory::ownedBy(signInRole(Role::MODERATOR))->create();
});

it('a visitor is not allowed to view manage podcast pages', function () {
    auth()->logout();
    get(route('podcasts.edit', $this->podcast))->assertRedirectToRoute('login');
    get(route('podcasts.create'))->assertRedirectToRoute('login');
    post(route('podcasts.store'), $this->podcast->toArray())->assertRedirectToRoute('login');
    put(route('podcasts.update', $this->podcast))->assertRedirectToRoute('login');
    delete(route('podcasts.destroy', $this->podcast))->assertRedirectToRoute('login');
});

it('denies access to edit a podcasts to an authenticated student', function () {
    auth()->logout();
    signInRole(Role::STUDENT);

    get(route('podcasts.edit', $this->podcast))->assertForbidden();
    get(route('podcasts.create'))->assertForbidden();
    post(route('podcasts.store'), $this->podcast->toArray())->assertForbidden();
    put(route('podcasts.update', $this->podcast))->assertForbidden();
    delete(route('podcasts.destroy', $this->podcast))->assertForbidden();
});

it('denies access to edit page to a moderator without access rights', function () {
    auth()->logout();

    //sign in another user
    signInRole(Role::MODERATOR);

    get(route('podcasts.edit', $this->podcast))->assertForbidden();
});

it('shows podcast create page a moderator user with all podcast page fields', function () {
    get(route('podcasts.create'))
        ->assertOk()
        ->assertSee(__('common.forms.title'))
        ->assertSee(__('common.forms.description'))
        ->assertSee('Host(s)')
        ->assertSee('Guest(s)')
        ->assertViewIs('backend.podcasts.create');
});

it('stores a new podcast in the database', function () {
    $newPodcast = Podcast::factory()->raw([
        'owner_id' => auth()->user()->id,
        'image' => null,
    ]);
    expect(Podcast::all()->count())->toBe(1);

    post(route('podcasts.store'), $newPodcast);
    expect(Podcast::all()->count())->toBe(2);
    assertDatabaseHas('podcasts', ['title' => $newPodcast['title']]);
});

it('show podcasts edit page for podcasts owner', function () {
    get(route('podcasts.edit', $this->podcast))
        ->assertOk()
        ->assertViewIs('backend.podcasts.edit');
});

it('denies updating a podcast to a non privileged moderator', function () {
    auth()->logout();

    //sign in another user
    signInRole(Role::MODERATOR);

    patch(route('podcasts.update', $this->podcast), ['title' => 'title_changed'])->assertForbidden();
});
