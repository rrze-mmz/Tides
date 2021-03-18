<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        $uploadedFile = UploadedFile::fake()->create('video.mp4', '10000','video/mp4');
        return [
            'original_file_name' => $uploadedFile->getClientOriginalName(),
            'disk'  => 'videos',
            'path' => '/videos/'.$uploadedFile->getClientOriginalName(),
            'width' => $this->faker->randomNumber(),
            'height' => '1280',
            'duration' => '720',
            'clip_id' => Clip::factory(),
            'type' => 'video/mp4'
        ];
    }
}
