<?php


namespace Tests\Setup;


use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use function PHPUnit\Framework\at;

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
            !empty($attributes)? $attributes : ['owner_id' => $this->user ?? User::factory()
        ]);

        Asset::factory($this->assetsCount)->create([
            'clip_id' => $clip->id
        ]);

        return $clip;
    }
}
