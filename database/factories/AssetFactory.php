<?php

namespace Database\Factories;

use App\Enums\Content;
use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $uploadedFile = UploadedFile::fake()->create('video.mp4', '10000', 'video/mp4');

        return [
            'original_file_name' => $uploadedFile->getClientOriginalName(),
            'disk' => 'videos',
            'path' => 'TIDES_TEST_CLIP',
            'width' => '1920',
            'height' => '1080',
            'duration' => '720',
            'clip_id' => Clip::factory(),
            'guid' => Str::uuid(),
            'type' => Content::PRESENTER,
            'player_preview' => '1_preview.png',
        ];
    }
}
