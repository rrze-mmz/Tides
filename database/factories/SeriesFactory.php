<?php

namespace Database\Factories;

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
            'title' => $title = $this->faker->sentence,
            'description'   => $this->faker->paragraph(50),
            'slug'  => $title,
            'owner_id'  => User::factory()->create()->id,
            'opencast_series_id' => null,
        ];
    }
}
