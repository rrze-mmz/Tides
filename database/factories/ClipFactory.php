<?php

namespace Database\Factories;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Clip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $title = $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'slug'  => $title,
            'owner_id' => User::factory()->create()->id,
        ];
    }
}
