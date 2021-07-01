<?php

namespace Database\Factories;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Clip::class;

    /**
     * Define the model's default state
     *
     * @return array
     */
    public function definition()
    {
        static $episode = 0;

        return [
            'title'       => $title = $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'slug'        => $title,
            'owner_id'    => User::factory()->create()->id,
            'posterImage' => null,
            'series_id'   => null,
            'episode'     => $episode++,
            'isPublic'    => true,
        ];
    }
}
