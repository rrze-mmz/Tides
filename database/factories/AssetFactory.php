<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

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
        $path = $this->faker->file(dirname(__DIR__, 2).'/storage/tests/');

        $uploadedFile = UploadedFile::fake()->create($path);

        return [
            'uploadedFile' => $uploadedFile,
            'clip_id' => Clip::factory(),
            'type' => 'video/mp4'
        ];
    }
}
