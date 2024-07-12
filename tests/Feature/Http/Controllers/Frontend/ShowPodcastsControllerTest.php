<?php

use App\Enums\Content;
use App\Enums\Role;
use App\Models\Asset;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Facades\Tests\Setup\PodcastFactory;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

uses()->group('frontend');

it('shows all public podcasts to visitors', function () {
    get(route('frontend.podcasts.index'))->assertOk()
        ->assertViewIs('frontend.podcasts.index')
        ->assertViewHas(['podcasts']);
});

it('shows no podcasts found if podcasts list is empty', function () {
    get(route('frontend.podcasts.index'))->assertSee('No podcasts found or published');
});

it('lists all published podcasts with episodes to visitors', function () {
    $podcast = Podcast::factory()->create();

    get(route('frontend.podcasts.index'))->assertDontSee($podcast->title);
});

it('shows a podcast edit link in index page for users with podcast edit rights', function () {
    $podcast = PodcastFactory::ownedBy(signInRole(Role::MODERATOR))
        ->withEpisodes(2)
        ->withAssets(1)
        ->create();

    auth()->logout();

    get(route('frontend.podcasts.index'))->assertDontSee(route('podcasts.edit', $podcast));

    signIn($podcast->owner);
    expect(auth()->user()->id)->toBe($podcast->owner->id);
    get(route('frontend.podcasts.index'))
        ->assertSee($podcast->title)
        ->assertSee(route('podcasts.edit', $podcast));
});

it('has a public episode page for a podcasts episode', function () {
    $podcast = Podcast::factory()->create();
    $episode = PodcastEpisode::factory()->create(['podcast_id' => $podcast->id]);
    $episode->addAsset(Asset::create([
        'original_file_name' => 'test.mp3',
        'disk' => 'videos',
        'path' => 'TIDES_TEST_CLIP',
        'width' => '0',
        'height' => '0',
        'duration' => '720',
        'guid' => Str::uuid(),
        'type' => Content::AUDIO,
        'player_preview' => '1_preview.png',
    ]));

    get(route('frontend.podcasts.episode.show', compact('podcast', 'episode')))
        ->assertOk();
});
