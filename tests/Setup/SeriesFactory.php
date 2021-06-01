<?php


namespace Tests\Setup;


use App\Models\Asset;
use App\Models\Clip;
use App\Models\Series;
use App\Models\User;

class SeriesFactory
{
    protected $clipsCount = 0;
    protected $assetsCount = 0;
    protected $user;

    public function withClips($count): static
    {
        $this->clipsCount = $count;

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

    public function create(): Series
    {
        $series = Series::factory()->create([
            'owner_id' => $user = $this->user ?? User::factory()
        ]);

        if($this->clipsCount > 0){
            $clips = Clip::factory($this->clipsCount)->create([
                'series_id' => $series->id,
                'owner_id'  => $user,
            ]);

            if($this->assetsCount > 0)
            {
                foreach ($clips as $clip)
                {
                    Asset::factory($this->assetsCount)->create([
                        'clip_id' => $clip->id
                    ]);
                }
            }
        }

        return $series;
    }
}
