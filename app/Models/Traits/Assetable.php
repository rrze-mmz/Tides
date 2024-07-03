<?php

namespace App\Models\Traits;

use App\Models\Asset;
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
}
