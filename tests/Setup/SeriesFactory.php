<?php


namespace Tests\Setup;


use App\Models\Clip;
use App\Models\Series;
use App\Models\User;

class SeriesFactory
{
    protected $clipsCount = 0;
    protected $user;

    public function withClips($count): static
    {
        $this->clipsCount = $count;

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
            Clip::factory($this->clipsCount)->create([
                'series_id' => $series->id,
                'owner_id'  => $user,
            ]);
        }

        return $series;
    }
}
