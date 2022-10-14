<?php

namespace Tests\Setup;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\Semester;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;

class SeriesFactory
{
    use WithFaker;

    protected int $clipsCount = 0;

    protected int $assetsCount = 0;

    protected bool $opencastSeriesID = false;

    protected bool $isPublic = true;

    protected User $user;

    public function withClips($count): static
    {
        $this->clipsCount = $count;

        return $this;
    }

    public function withOpencastID(bool $opencastSeriesID = true): static
    {
        $this->opencastSeriesID = $opencastSeriesID;

        return $this;
    }

    public function notPublic(bool $isPublic = false): static
    {
        $this->isPublic = $isPublic;

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
        //use ramsey/uuid because faker shows an error
        $series = Series::factory()->create([
            'owner_id' => $user = $this->user ?? User::factory(),
            'opencast_series_id' => ($this->opencastSeriesID) ? Uuid::uuid1()->toString() : '',
            'is_public' => $this->isPublic,
        ]);

        if ($this->clipsCount > 0) {
            Clip::factory($this->clipsCount)->create([
                'series_id' => $series->id,
                'owner_id' => $user,
                'semester_id' => Semester::current()->get()->first()->id,
            ]);

            if ($this->assetsCount > 0) {
                $series->clips()->each(function ($clip) {
                    Asset::factory($this->assetsCount)->create([
                        'clip_id' => $clip->id,
                    ]);
                });
            }
        }

        return $series;
    }
}
