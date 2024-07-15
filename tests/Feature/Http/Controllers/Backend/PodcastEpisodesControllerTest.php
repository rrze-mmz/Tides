<?php

use App\Enums\Role;
use Facades\Tests\Setup\PodcastFactory;

use function Pest\Laravel\get;

uses()->group('backend');

beforeEach(function () {
    $this->podcast = PodcastFactory::ownedBy(signInRole(Role::MODERATOR))->withEpisodes(2)->create();
});

it('denies access to edit a podcast episode to non authorized users', function () {
    $episode = $this->podcast->episodes->random();
    auth()->logout();
    get(route('podcasts.episodes.edit', [$this->podcast, $episode]))->assertRedirectToRoute('login');

    signInRole(Role::USER);
    get(route('podcasts.episodes.edit', [$this->podcast, $episode]))->assertForbidden();
    auth()->logout();

    signInRole(Role::STUDENT);
    get(route('podcasts.episodes.edit', [$this->podcast, $episode]))->assertForbidden();
    auth()->logout();
});

it('shows podcast edit page and all fields to moderator owner ', function () {
    $episode = $this->podcast->episodes->random();

    get(route('podcasts.episodes.edit', ([$this->podcast, $episode])))->assertOk();
});
