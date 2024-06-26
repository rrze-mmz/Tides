<?php

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

uses()->group('backend');

beforeEach(function () {
    Storage::fake('local');

    $this->podcast = Podcast::factory()->create();
    // Create dummy folders for testing
    Storage::makeDirectory('podcasts-files/covers/courseID_'.$this->podcast->old_podcast_id);

    // Create test podcasts in the database
    Podcast::factory(2)->create();
});

it('renames folders correctly when matching old  pattern', function () {
    $oldFolderPath = 'podcasts-files/covers/courseID_'.$this->podcast->old_podcast_id;
    $newFolderPath = 'podcasts-files/covers/podcastID_'.$this->podcast->id;
    artisan('podcasts:rename-folders')->expectsOutput("Renamed $oldFolderPath to $newFolderPath")->assertExitCode(0);

    Storage::assertMissing($oldFolderPath);
    Storage::assertExists($newFolderPath);
});

it('skips folders that do not match the pattern', function () {
    Storage::makeDirectory('podcasts-files/covers/invalid_folder');
    artisan('podcasts:rename-folders')
        ->doesntExpectOutput('Renamed podcasts-files/covers/invalid_folder to')
        ->assertExitCode(0);

    Storage::assertExists('podcasts-files/covers/invalid_folder');
});

it('moves and renames also episode files inside the podcast directory', function () {
    $episode = PodcastEpisode::factory()->create(['podcast_id' => $this->podcast->id]);
    Storage::makeDirectory('podcasts-files/covers/clipID_'.$episode->old_episode_id);
    $oldFolderPath = 'podcasts-files/covers/clipID_'.$episode->old_episode_id;
    $newFolderPath = 'podcasts-files/covers/podcastID_'.$this->podcast->id.'/episodeID_'.$episode->id;

    artisan('podcasts:rename-folders')
        ->expectsOutput("Renamed $oldFolderPath to $newFolderPath")
        ->assertExitCode(0);

    Storage::assertMissing($oldFolderPath);
    Storage::assertExists($newFolderPath);
});
