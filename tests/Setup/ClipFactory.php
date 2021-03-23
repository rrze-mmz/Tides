<?php


namespace Tests\Setup;


use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;

class ClipFactory {

    protected $assetsCount = 0;
    protected $user;

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

    public function create()
    {
        $clip = Clip::factory()->create([
            'owner_id' => $this->user ?? User::factory()
        ]);

        Asset::factory($this->assetsCount)->create([
            'clip_id' => $clip->id
        ]);

        return $clip;
    }
}
