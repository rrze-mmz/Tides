<?php

use App\Enums\Role;
use App\Models\PodcastEpisode;
use Facades\Tests\Setup\PodcastFactory;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses()->group('backend');

beforeEach(function () {
    $this->podcast = PodcastFactory::ownedBy(signInRole(Role::MODERATOR))->withEpisodes(2)->create();
    $this->episode = $this->podcast->episodes->random();
});

it('denies access to create,edit or delete a podcast episode to non authorized users', function () {
    auth()->logout();
    get(route('podcasts.episodes.create', $this->podcast))->assertRedirectToRoute('login');
    post(route('podcasts.episodes.create', $this->podcast), [])->assertRedirectToRoute('login');
    get(route('podcasts.episodes.edit', [$this->podcast, $this->episode]))->assertRedirectToRoute('login');
    put(route('podcasts.episodes.edit', [$this->podcast, $this->episode]), [])->assertRedirectToRoute('login');
    delete(route('podcasts.episodes.edit', [$this->podcast, $this->episode]))->assertRedirectToRoute('login');

    signInRole(Role::USER);
    get(route('podcasts.episodes.create', $this->podcast))->assertForbidden();
    post(route('podcasts.episodes.create', $this->podcast), [])->assertForbidden();
    get(route('podcasts.episodes.edit', [$this->podcast, $this->episode]))->assertForbidden();
    put(route('podcasts.episodes.edit', [$this->podcast, $this->episode]), [])->assertForbidden();
    delete(route('podcasts.episodes.edit', [$this->podcast, $this->episode]))->assertForbidden();
    auth()->logout();

    signInRole(Role::STUDENT);
    get(route('podcasts.episodes.create', $this->podcast))->assertForbidden();
    post(route('podcasts.episodes.create', $this->podcast), [])->assertForbidden();
    get(route('podcasts.episodes.edit', [$this->podcast, $this->episode]))->assertForbidden();
    put(route('podcasts.episodes.edit', [$this->podcast, $this->episode]), [])->assertForbidden();
    delete(route('podcasts.episodes.edit', [$this->podcast, $this->episode]))->assertForbidden();
    auth()->logout();
});

it('shows create episode page for a podcast to podcast owner', function () {
    get(route('podcasts.episodes.create', $this->podcast))
        ->assertViewIs('backend.podcasts.episode.create')
        ->assertViewHas('podcast', $this->podcast)
        ->assertOk();
});

it('stores the podcast to the database', function () {
    expect($this->podcast->episodes->count())->toBe(2);
    post(route('podcasts.episodes.store', $this->podcast), PodcastEpisode::factory()->raw([
        'image' => null,
    ]))
        ->assertRedirect();
    $this->podcast->refresh();

    expect($this->podcast->episodes->count())->toBe(3);
});

it('shows podcast edit page and all fields to moderator owner ', function () {
    get(route('podcasts.episodes.edit', ([$this->podcast, $this->episode])))->assertOk();
});
