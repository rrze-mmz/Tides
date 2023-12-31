<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Series::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $title = $this->faker->sentence(),
            'description' => $this->faker->paragraph(2),
            'organization_id' => '1',
            'slug' => $title,
            'owner_id' => User::factory()->create()->id,
            'opencast_series_id' => null,
            'is_public' => true,
            'image_id' => Image::factory()->create()->id,
        ];
    }
}
