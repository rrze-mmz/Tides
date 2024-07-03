<?php

namespace Tests\Setup;

use App\Enums\Content;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;

class ClipFactory
{
    protected int $assetsCount = 0;

    protected User $user;

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

    public function create(array $attributes = [])
    {
        $clip = Clip::factory()->create(
            ! empty($attributes) ? $attributes : ['owner_id' => $this->user ?? User::factory(),
            ]
        );

        if ($this->assetsCount > 0) {
            $assets = Asset::factory($this->assetsCount)->create([
                'path' => $clip->folder_id.'/video.mp4',
            ]);
            $assets->each(function ($asset) use ($clip) {
                $clip->addAsset($asset);
            });
            $smilFile = Asset::factory()->create([
                'original_file_name' => 'presenter.smil',
                'type' => Content::SMIL,
            ]);
            $clip->addAsset($smilFile);
        }

        return $clip;
    }
}
