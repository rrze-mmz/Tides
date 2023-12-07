<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Presenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresenterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presenter::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'academic_degree_id' => '1',
            'first_name' => $firstName = $this->faker->firstName(),
            'last_name' => $lastName = $this->faker->lastName(),
            'slug' => $firstName.'-'.$lastName,
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'image_id' => Image::factory()->create(),
        ];
    }
}
