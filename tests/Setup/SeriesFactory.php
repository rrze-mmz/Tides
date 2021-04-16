<?php


namespace Tests\Setup;


use App\Models\Clip;
use App\Models\Series;
use App\Models\User;

class SeriesFactory
{
    protected $clipsCount = 0;
    protected $user;

    public function withClips($count)
    {
        $this->clipsCount = $count;

        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }

    public function create()
    {
        $series = Series::factory()->create([
            'owner_id' => $this->user ?? User::factory()
        ]);

        Clip::factory($this->clipsCount)->create([
            'series_id' => $series->id
        ]);

        return $series;
    }
}
