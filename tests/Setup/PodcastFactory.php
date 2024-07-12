<?php

namespace Tests\Setup;

use App\Enums\Content;
use App\Models\Asset;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class PodcastFactory
{
    use WithFaker;

    protected int $episodesCount = 0;

    protected int $assetsCount = 0;

    protected User $user;

    public function withEpisodes($count): static
    {
        $this->episodesCount = $count;

        return $this;
    }

    public function withAssets($count): static
    {
        $this->assetsCount = $count;

        return $this;
    }

    public function ownedBy($user): static
    {
        $this->user = $user;

        return $this;
    }

    public function create($count = 1): Podcast|Collection
    {
        if ($count > 1) {
            $podcast = Podcast::factory($count)->create([
                'owner_id' => $user = $this->user ?? User::factory(),
                'image_id' => config('settings.portal.default_image_id'),
            ]);
            $podcast->each(function ($podcast) use ($user) {
                if ($this->episodesCount > 0) {
                    PodcastEpisode::create($this->episodesCount)->create([
                        'podcast_id' => $podcast->id,
                        'owner_id' => $user,
                        'image_id' => config('settings.portal.default_image_id'),
                    ]);
                    if ($this->assetsCount > 0) {
                        $podcast->episodes()->each(function (PodcastEpisode $podcastEpisode) {
                            $assets = Asset::factory($this->assetsCount)
                                ->create(['type' => Content::AUDIO]);
                            $assets->each(function ($asset) use ($podcastEpisode) {
                                $podcastEpisode->addAsset($asset);
                            });
                        });
                    }
                }
            });
        } else {
            $podcast = Podcast::factory()->create([
                'owner_id' => $user = $this->user ?? User::factory(),
                'image_id' => config('settings.portal.default_image_id'),
            ]);
            if ($this->episodesCount > 0) {
                PodcastEpisode::factory($this->episodesCount)->create([
                    'podcast_id' => $podcast->id,
                    'owner_id' => $user,
                    'image_id' => config('settings.portal.default_image_id'),
                ]);
            }
            if ($this->assetsCount > 0) {
                $podcast->episodes()->each(function (PodcastEpisode $podcastEpisode) {
                    $assets = Asset::factory($this->assetsCount)
                        ->create(['type' => Content::AUDIO]);
                    $assets->each(function ($asset) use ($podcastEpisode) {
                        $podcastEpisode->addAsset($asset);
                    });
                });
            }
        }

        return $podcast;
    }
}
