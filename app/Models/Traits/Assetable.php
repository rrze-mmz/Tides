<?php

namespace App\Models\Traits;

use App\Enums\Content;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Assetable
{
    public function addAsset(Asset $asset): Asset
    {
        $this->assets()->attach($asset);

        return $asset;
    }

    public function assets(): MorphToMany
    {
        return $this->morphToMany(Asset::class, 'assetable')->withTimestamps();
    }

    /**
     * Fetch all assets for a clip by type
     */
    public function getAssetsByType(Content $content): MorphToMany
    {
        return $this->assets()->where(function ($q) use ($content) {
            $q->where('type', $content());
        });
    }

    /**
     * Return caption asset for the clip
     */
    public function getCaptionAsset(): ?Asset
    {
        return $this->assets->filter(function ($asset) {
            return $asset->type == Content::CC() && ! $asset->is_deleted;
        })->first();
    }

    public function latestAsset(): Model|MorphToMany|null
    {
        return $this->assets()
            ->orderByDesc('width', 'type')
            ->limit(1)->first();
        //        return $this->assets()->first();
    }
}
